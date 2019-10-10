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
        try:
            version = re.findall(r"(?<=GLOBALS\['version'\])?[0-9].*(?=';)", open(helper).read())[0]
        except:
            log.error(f"Error encountered when parsing '{helper}'.")
            return None

        return version
        
    return None


def database_type():
    """Return the database type."""
    return os.getenv("CONNECTION_DRIVER") or "sqlite"


def set_maintenance_mode(install_path, value):
    """Set maintenance mode to enabled or disabled."""
    log.debug(f"Setting maintenance mode to '{value}'...")
    maintenance_file = install_path + "storage/framework/down"
    if value == "enabled":
        open(maintenance_file, "a").close()
    else:
        try:
            os.remove(maintenance_file)
        except:
            log.error(f"Could not remove '{maintenance_file}'.")


def backup_database(database_type, backup_dir, install_path, current_time):
    """Backup a MunkiReport database."""
    if database_type == "mysql":
        username = os.getenv("CONNECTION_USERNAME")
        password = os.getenv("CONNECTION_PASSWORD")
        database = os.getenv("CONNECTION_DATABASE")
        backup_file = backup_dir + database + current_time + ".bak"
        cmd = f"/usr/local/opt/mysql-client/bin/mysqldump --user={username} --password={password} {database} > {backup_file}"
        log.info("Backing up database to '{}'...".format(backup_file))
        try:
            subprocess.check_output(cmd, shell=True)
        except subprocess.CalledProcessError as e:
            log.error(f"mysqldump failed with error: '{e}'.")
            return False

    elif database_type == "sqlite":
        try:
            shutil.copyfile(
                install_path + "app/db/db.sqlite",
                backup_dir + "db" + current_time + ".sqlite.bak",
            )
        except:
            log.error("Errors encountered when backing up database.")
            return False
    
    return True


def backup_files(backup_dir, install_path, current_time):
    """Create file backup of install."""
    backup_dir = os.path.join(backup_dir, "munkireport", current_time)
    log.info(f"Backing up files to '{backup_dir}'...")
    try:
        os.mkdir(backup_dir)
    except:
        log.error(f"Could not make backup directory '{backup_dir}'.")
        return False
    try:
        copy_tree(install_path, backup_dir)
    except:
        log.error(f"Errors encountered when running copy_tree({install_path}, {backup_dir}).")
        return False
    
    return True


def get_latest_version():
    """Return MR latest version"""
    mr_api = "https://api.github.com/repos/munkireport/munkireport-php/releases/latest"
    try:
        with urllib.request.urlopen(mr_api) as response:
            data = json.loads(response.read())
        return data["tag_name"].strip("v")
    except:
        log.error("Errors encountered when grabbing latest version.")
        return "UNKNOWN"


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
        "--upgrade", help="Attempt to upgrade MunkiReport.", action='store_true', 
        default=False
    )
    parser.add_argument(
        "--version",
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

    if args.upgrade:
        if current_version < latest_version:
            log.info(f"Starting upgrade of {install_path}...")
            if not set_maintenance_mode(install_path, "enabled"):
                exit(1)

            current_time = datetime.datetime.now().strftime("%Y%m%d%H%M")
            # backup database
            if not backup_database(database_type, args.backup_dir, install_path, current_time):
                exit(1)

            # backup files
            if not backup_files(args.backup_dir, install_path, current_time):
                exit(1)

            # attempt git pull for update
            try:
                log.info("Starting Git pull...")
                subprocess.check_output(["git", "pull"])
            except subprocess.CalledProcessError as e:
                log.error(f"Git pull failed with the following output: '{e}'. Exiting...")
                exit(1)

            log.info("Git pull complete.")
            os.chdir(install_path)

            # run composer
            log.info("Running composer...")
            cmd = ["/usr/local/bin/composer", "update", "--no-dev"]
            log.debug(f"Running command {' '.join(cmd)}...")
            try:
                subprocess.check_output(cmd)
            except subprocess.CalledProcessError as e:
                log.error(f"Composer failed with the following output: '{e}'. Exiting...")
                exit(1)

            log.info("Composer complete.")
            os.chdir(f"{install_path}/build/")

            # Run Migrations
            log.info("Running migrations...")
            cmd = f"/usr/bin/php {install_path}database/migrate.php"
            log.debug(f"Running command: '{' '.join(cmd)}'")
            try:
                proc = subprocess.check_output(cmd)
            except subprocess.CalledProcessError as e:
                log.error("Migrations failed. Error output: '{e}'. Exiting...")
                exit(1)

            log.info("Migrations complete.")

            # disable maintenance mode
            set_maintenance_mode(install_path, "disabled")
            log.info("Upgrade complete.")
        
        else:
            log.info("No version upgrade available.")
