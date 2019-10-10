#!/usr/bin/env python3

"""Script for updating MunkiReport"""

import argparse
import datetime
from dotenv import load_dotenv
from distutils.dir_util import copy_tree
import coloredlogs
import json
import logging
import os
import re
import shutil
import subprocess
import urllib.request


# log output to console
log = logging.getLogger()
coloredlogs.install(
    fmt="[%(asctime)s] - [%(levelname)-8s] - %(message)s", level="INFO", logger=log
)

# load environment variables
load_dotenv()


def get_current_version(install_path):
    """Return current build version"""
    helper = install_path + "app/helpers/site_helper.php"
    if os.path.exists(helper):
        version = [
            re.findall(r"(?<=GLOBALS\['version'\])?[0-9].*(?=';)", line)
            for line in open(helper)
        ]
        return [x for x in version]

    return None


def database_type():
    """Return the database type."""
    return os.getenv("CONNECTION_DRIVER") or "sqlite"


def set_maintenance_mode(install_path, value):
    """Set maintenance mode to down or remove"""
    log.debug(f"Setting maintenance mode to '{value}'...")
    if value == "down":
        open(install_path + "storage/framework/" + value, "a").close()
    else:
        os.remove(install_path + "storage/framework/down")


def backup_database(database_type, backup_dir, install_path, current_time):
    """Backup a MunkiReport database."""
    if database_type == "mysql":
        username = os.getenv("CONNECTION_USERNAME")
        password = os.getenv("CONNECTION_PASSWORD")
        database = os.getenv("CONNECTION_DATABASE")
        backup_file = backup_dir + database + current_time + ".bak"
        cmd = f"/usr/local/opt/mysql-client/bin/mysqldump --user={username} --password={password} {database} > {backup_file}"
        log.info("Backing up database to '{}'...".format(backup_file))
        subprocess.Popen(cmd, shell=True)

    elif database_type == "sqlite":
        shutil.copyfile(
            install_path + "app/db/db.sqlite",
            backup_dir + "db" + current_time + ".sqlite.bak",
        )


def backup_files(backup_dir, install_path, current_time):
    """Create file backup of install."""
    final_dir = os.path.join(backup_dir, "munkireport", current_time)
    log.info(f"Backing up files to '{final_dir}'...")
    os.mkdir(final_dir)
    copy_tree(install_path, final_dir)


def get_latest_version():
    """Return MR latest version"""
    mr_api = "https://api.github.com/repos/munkireport/munkireport-php/releases/latest"
    with urllib.request.urlopen(mr_api) as response:
        data = json.loads(response.read())
    return data["tag_name"].strip("v")


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Manage a MunkiReport install.")
    group = parser.add_mutually_exclusive_group()
    group.add_argument(
        "-i",
        "--info",
        action="store_true",
        help="Print info on the MunkiReport install.",
    )
    parser.add_argument(
        "--no-backup", help="Do not take any backups before upgrading.", default=False
    )
    parser.add_argument("--backup-dir", help="Directory to back up to.", default="/tmp")
    parser.add_argument(
        "--install-path",
        help="Install path for MunkiReport.",
        default=os.path.dirname(os.path.realpath(__file__)).strip("build"),
    )
    parser.add_argument(
        "--upgrade", help="Attempt to upgrade MunkiReport", default="False"
    )
    parser.add_argument(
        "--upgrade-version",
        help="Version of MunkiReport to upgrade to.",
        default="latest",
    )
    args = parser.parse_args()

    install_path = args.install_path
    current_version = get_current_version(install_path)
    latest_version = get_latest_version()
    database_type = database_type()

    if not current_version:
        log.error(
            f"The directory '{install_path}' does not appear to be a valid MunkiReport install."
        )
        exit()

    log.info(f"Current version: {current_version}")
    log.info(f"Latest version:  {latest_version}")
    log.info(f"Install path:    {install_path}")
    log.info(f"Database type:   {database_type}")

    if args.info:
        exit()

    if current_version < latest_version:
        log.info(f"Starting upgrade of {install_path}...")
        set_maintenance_mode(install_path, "down")

        current_time = datetime.datetime.now().strftime("%Y%m%d%H%M")
        # backup database
        backup_database(database_type, args.backup_dir, install_path, current_time)

        # backup files
        backup_files(args.backup_dir, install_path, current_time)

        # attempt git pull for update
        try:
            log.info("Starting Git pull...")
            proc = subprocess.check_output(["git", "pull"])
        except subprocess.CalledProcessError as e:
            log.error(f"Git pull failed with the following output: '{e}'. Exiting...")
            exit(1)

        log.info("Git pull complete.")

        log.info("Running composer...")
        os.chdir(install_path)
        try:
            proc = subprocess.check_output(
                ["/usr/local/bin/composer", "update", "--no-dev"]
            )
        except subprocess.CalledProcessError as e:
            log.error(f"Composer failed with the following output: '{e}'. Exiting...")
            exit(1)

        log.info("Composer complete.")
        os.chdir(f"{install_path}/build/")

        # Run Migrations
        log.info("Running migrations...")
        cmd = f"/usr/bin/php {install_path}database/migrate.php"
        log.debug(f"Running command: '{cmd}'")
        try:
            proc = subprocess.check_output(cmd)
        except subprocess.CalledProcessError as e:
            log.error("Migrations failed. Error output: '{e}'. Exiting...")
            exit(1)

        log.info("Migrations complete.")

        # turn off maintenance mode
        set_maintenance_mode(install_path, "up")
        log.info("Upgrade complete.")
