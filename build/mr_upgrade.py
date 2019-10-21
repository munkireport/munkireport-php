#!/usr/bin/env python3

"""
Script for upgrading MunkiReport.
"""

import argparse
import datetime
import json
import logging
import operator
import os
import re
import shutil
import subprocess
import urllib.request
from distutils.dir_util import copy_tree

import coloredlogs
from dotenv import load_dotenv

# log output to console
log = logging.getLogger()
coloredlogs.install(
    fmt="[%(asctime)s] - [%(levelname)-8s] - %(message)s", level="INFO", logger=log
)

# load environment variables
load_dotenv()


def run_command(args):
    """Run a given command."""
    log.debug(f"Running command '{' '.join(args)}'...'")
    try:
        subprocess.run(args, capture_output=True, check=True)
    except subprocess.CalledProcessError as e:
        log.error(
            f"Command '{' '.join(args)}' failed with the following output: '{e.stderr.decode('utf8')}'. Exiting..."
        )
        return False

    log.debug(f"Command '{' '.join(args)}' completed successfully.")
    return True


def get_current_version(install_path):
    """Return current build version"""
    helper = install_path + "app/helpers/site_helper.php"
    if os.path.exists(helper):
        try:
            version = re.findall(
                r"(?<=GLOBALS\['version'\])?[0-9].*(?=';)", open(helper).read()
            )[0]
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
        try:
            open(maintenance_file, "a").close()
            return True
        except:
            log.error(f"Could not create '{maintenance_file}'.")
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
        host = os.getenv("CONNECTION_HOST")
        backup_file = os.path.join(backup_dir, database + "-" + current_time + ".bak")
        cmd = f"/usr/local/opt/mysql-client/bin/mysqldump --user={username} --password={password} -h {host} {database} > {backup_file}"
        log.info("Backing up database to '{}'...".format(backup_file))
        if not run_command(cmd):
            return False

        log.info("Backup completed successfully.")

    elif database_type == "sqlite":
        try:
            shutil.copyfile(
                install_path + "app/db/db.sqlite",
                backup_dir + "db" + current_time + ".sqlite.bak",
            )
        except OSError as e:
            log.error(f"The following error encountered when backing up database: {e}.")
            return False

    return True


def backup_files(backup_dir, install_path, current_time):
    """Create file backup of install."""
    backup_dir = os.path.join(backup_dir, "munkireport", current_time)
    log.info(f"Backing up files to '{backup_dir}'...")
    if not os.path.exists(backup_dir):
        try:
            os.makedirs(backup_dir)
        except:
            log.error(f"Could not make backup directory '{backup_dir}'.")
            return False
    else:
        log.debug(f"Backup dir {backup_dir} already exists, continuing...")
    try:
        copy_tree(install_path, backup_dir)
    except:
        log.error(
            f"Errors encountered when running copy_tree({install_path}, {backup_dir})."
        )
        return False

    return True


def get_versions():
    """Return MR versions"""
    mr_api = "https://api.github.com/repos/munkireport/munkireport-php/releases"
    log.debug(f"Querying '{mr_api}' for latest release...")
    versions = {}
    try:
        with urllib.request.urlopen(mr_api) as response:
            data = json.loads(response.read())

        for version in data:
            versions[version["tag_name"].strip("v")] = version["target_commitish"]
        log.debug(f"Found versions: {versions}.")

    except:
        log.error("Errors encountered when grabbing latest version.")

    return versions


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
        "--no-backup",
        help="Do not take any backups before upgrading.",
        action="store_true",
        default=False,
    )
    parser.add_argument("--backup-dir", help="Directory to back up to.", default="/tmp")
    parser.add_argument(
        "--install-path",
        help="Install path for MunkiReport.",
        default=os.path.dirname(os.path.realpath(__file__)).strip("build"),
    )
    parser.add_argument(
        "--upgrade",
        help="Attempt to upgrade MunkiReport.",
        action="store_true",
        default=False,
    )
    parser.add_argument(
        "-v",
        "--verbose",
        action="store_true",
        default=False,
        help="Enable verbose logging.",
    )
    parser.add_argument(
        "--version", type=str, default="latest", help="Version to upgrade to."
    )
    args = parser.parse_args()

    if args.verbose:
        coloredlogs.install(
            fmt="[%(asctime)s] - [%(levelname)-8s] - %(message)s",
            level="DEBUG",
            logger=log,
        )

    install_path = args.install_path
    current_version = get_current_version(install_path)
    desired_version = args.version
    versions = get_versions()
    latest_version = max(versions.items(), key=operator.itemgetter(0))[0]
    if args.version == "latest":
        desired_version = latest_version

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
        if current_version >= latest_version and args.version == "latest":
            log.info("No version upgrade available.")

        else:
            if desired_version not in versions.keys():
                log.error(f"Version '{desired_version}' was not found. Exiting...")
                exit()

            log.info(f"Installing version {desired_version}...")
            if not set_maintenance_mode(install_path, "enabled"):
                exit()

            current_time = datetime.datetime.now().strftime("%Y%m%d%H%M")

            if not args.no_backup:
                # backup database
                if not backup_database(
                    database_type, args.backup_dir, install_path, current_time
                ):
                    exit()

                # backup files
                if not backup_files(args.backup_dir, install_path, current_time):
                    exit()

            # attempt git pull for update
            log.info("Starting Git pull...")
            if not run_command(["git", "pull", "origin", "master"]):
                exit()

            log.info("Git pull complete.")

            # switch to the specific commit for the version
            log.info(f"Switching to commit for version {desired_version}...")
            commit = versions[desired_version]
            log.debug(f"Commit for version {desired_version} is {commit}.")

            if not run_command(["git", "checkout", commit]):
                exit()

            log.info("Git checkout complete.")

            # run composer
            os.chdir(install_path)
            log.info("Running composer...")
            if not run_command(["/usr/local/bin/composer", "update", "--no-dev"]):
                exit()

            log.info("Composer complete.")

            # run migrations
            os.chdir(f"{install_path}/build/")
            log.info("Running migrations...")
            if not run_command(["/usr/bin/php", f"{install_path}database/migrate.php"]):
                exit()

            log.info("Migrations complete.")

            # disable maintenance mode
            set_maintenance_mode(install_path, "disabled")
            log.info("Upgrade complete.")
