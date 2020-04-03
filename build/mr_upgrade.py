#!/usr/bin/env python3

"""Script for upgrading MunkiReport."""

import argparse
import datetime
import json
import logging
import operator
import os
import re
import shutil
import sqlite3
import subprocess
import urllib.request
from distutils.dir_util import copy_tree

import coloredlogs
from dotenv import load_dotenv

# log output to console
log = logging.getLogger()
coloredlogs.install(
    fmt="[%(asctime)s] [%(levelname)-8s] %(message)s", level="INFO", logger=log
)

# load environment variables
load_dotenv()


def run_command(args: list, suppress_output: bool = False) -> bool:
    """Run a given command."""
    log.debug(
        f"Running command '{' '.join(args)}', suppress_output={suppress_output}...'"
    )
    try:
        subprocess.run(args, capture_output=True, check=True)
    except subprocess.CalledProcessError as e:
        if not suppress_output:
            log.error(
                f"Command '{' '.join(args)}' failed with the following output: '{e.stderr.decode('utf8')}'. Exiting..."
            )
        return False

    log.debug(f"Command '{' '.join(args)}' completed successfully.")
    return True


def get_current_version(install_path: str) -> str:
    """Return current build version."""
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


def get_database_type() -> str:
    """Return the database type."""
    return os.getenv("CONNECTION_DRIVER") or "sqlite"


def set_maintenance_mode(install_path: str, value: str) -> None:
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


def backup_database(backup_dir: str, install_path: str, current_time: str) -> bool:
    """Backup a MunkiReport database."""
    database_type = get_database_type()

    if database_type == "mysql":
        database = os.getenv("CONNECTION_DATABASE")
        backup_file = os.path.join(backup_dir, database + "-" + current_time + ".bak")
        cmd = [
            "/usr/local/opt/mysql-client/bin/mysqldump",
            f"--user={os.getenv('CONNECTION_USERNAME')}",
            f"--password={os.getenv('CONNECTION_PASSWORD')}",
            f"--host={os.getenv('CONNECTION_HOST')}",
            "--databases",
            os.getenv("CONNECTION_DATABASE"),
            f"--result-file={backup_file}",
            "--skip-comments",
        ]
        log.info("Backing up database to '{}'...".format(backup_file))
        if not run_command(cmd):
            return False

        log.info("Backup completed successfully.")

    elif database_type == "sqlite":
        database_path = os.getenv("CONNECTION_DATABASE")

        # ensure that the database path is defined and exists
        if database_path:
            if not os.path.isfile(database_path):
                log.error(f"Could not find sqlite database at path '{database_path}'.")
                return False
        else:
            log.error(f"'CONNECTION_DATABASE' is undefined in your environment.")
            return False

        # backup the database to the backup directory with the current time
        backup_file = backup_dir + "/db_" + current_time + ".sqlite.bak"
        log.info(f"Backing up database to '{backup_file}'...")

        conn = sqlite3.connect(database_path)
        try:
            with open(backup_file, "w") as f:
                for line in conn.iterdump():
                    f.write("%s\n" % line)

        except OSError as e:
            log.error(f"The following error encountered when backing up database: {e}.")
            return False

    return True


def restore_database(backup_file: str, install_path: str) -> bool:
    """Restore a MunkiReport database from a backup."""
    database_type = get_database_type()

    if not os.path.isfile(backup_file):
        log.error(f"Backup file '{backup_file}' does not exist!'")
        return False

    log.info(f"Restoring database from backup file '{backup_file}'...")
    database = os.getenv("CONNECTION_DATABASE")

    if database_type == "mysql":
        cmd = [
            "/usr/local/opt/mysql-client/bin/mysql",
            f"--user={os.getenv('CONNECTION_USERNAME')}",
            f"--password={os.getenv('CONNECTION_PASSWORD')}",
            f"--host={os.getenv('CONNECTION_HOST')}",
            f"--database={database}",
            f"--execute=source {backup_file}",
        ]
        log.debug(f"Restoring database '{database}' from '{backup_file}'...'")
        if not run_command(cmd):
            return False

    elif database_type == "sqlite":
        database_path = os.getenv("CONNECTION_DATABASE")

        # ensure that the database path is defined and exists
        if database_path:
            if not os.path.isfile(database_path):
                log.error(f"Could not find sqlite database at path '{database_path}'.")
                return False
        else:
            log.error(f"'CONNECTION_DATABASE' is undefined in your environment.")
            return False

        # move the old database file to db.sqlite.old
        log.debug(
            f"Renaming current database from '{database_path}' to '{database_path}.old'..."
        )
        try:
            shutil.move(database_path, database_path + ".old")
        except OSError as e:
            log.error(f"The following error encountered when backing up database: {e}.")
            return False

        # import from the backup
        log.debug(f"Rename successful. Restoring database from '{backup_file}'...")
        try:
            conn = sqlite3.connect(database_path)
            c = conn.cursor()
        except:
            log.error(
                f"Unable to instantiate sqlite cursor with database {database_path}."
            )
            return False

        try:
            with open(backup_file, "r") as bf:
                for line in bf:
                    c.execute(line)
        except:
            log.error("Errors encountered when reading backup file.")
            return False

    log.info("Database restoration completed successfully.")
    return True


def backup_files(backup_dir: str, install_path: str, current_time: str) -> bool:
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


def get_versions() -> dict:
    """Return MR versions."""
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
        action="store_true",
        default=False,
        help="Do not take any backups before upgrading.",
    )
    parser.add_argument(
        "--backup-dir", type=str, help="Directory to back up to.", default="/tmp"
    )
    parser.add_argument(
        "--install-path",
        type=str,
        default=os.path.dirname(os.path.realpath(__file__)).strip("build"),
        help="Install path for MunkiReport.",
    )
    parser.add_argument(
        "--upgrade",
        action="store_true",
        default=False,
        help="Attempt to upgrade MunkiReport.",
    )
    parser.add_argument("--restore", type=str, help="Restore database from backup.")
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
    parser.add_argument(
        "--no-composer",
        action="store_true",
        default=False,
        help="Don't run composer after upgrade.",
    )
    parser.add_argument(
        "--no-migrations",
        action="store_true",
        default=False,
        help="Don't run migrations after upgrade.",
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

    database_type = get_database_type()

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

            # enable maintenance mode
            if not set_maintenance_mode(install_path, "enabled"):
                exit()

            current_time = datetime.datetime.now().strftime("%Y%m%d%H%M")

            if not args.no_backup:
                # backup database
                if not backup_database(args.backup_dir, install_path, current_time):
                    exit()

                # backup files
                if not backup_files(args.backup_dir, install_path, current_time):
                    exit()

            # if we go back to an old enough version, mr_upgrade wont exist so we wont
            # be able to run an upgrade due to local changes, so stash the upgrade script.
            run_command(["git", "stash", "save", "mr_upgrade.py"])

            # attempt git fetch for update
            log.info("Starting Git fetch...")
            if not run_command(["git", "fetch", "origin", "master"]):
                exit()

            log.info("Git fetch complete.")

            # switch to the specific commit for the version
            log.info(f"Switching to commit for version {desired_version}...")
            commit = versions[desired_version]
            log.debug(f"Commit for version {desired_version} is {commit}.")

            # try to checkout to the specific version (v5.1.0, for example),
            # which makes it wasier to see the version you are currently
            # running when doing a git branch
            if not run_command(
                ["git", "checkout", f"v{desired_version}"], suppress_output=True
            ):
                if not run_command(["git", "checkout", commit]):
                    exit()
                else:
                    log.info(
                        f"No tag was found for version {desired_version}, so commit was checked out instead of version tag."
                    )

            log.info("Git checkout complete.")

            if not args.no_composer:
                # run composer
                os.chdir(install_path)
                log.info("Running composer...")
                if not run_command(["/usr/local/bin/composer", "update", "--no-dev"]):
                    exit()

                log.info("Composer complete.")

            if not args.no_migrations:
                # run migrations
                os.chdir(f"{install_path}/build/")
                log.info("Running migrations...")
                if not run_command(
                    ["/usr/bin/php", f"{install_path}database/migrate.php"]
                ):
                    exit()

                log.info("Migrations complete.")

            # pull the latest version of the mr_upgrade script
            run_command(["git", "checkout", "master", "mr_upgrade.py"])

            # disable maintenance mode
            set_maintenance_mode(install_path, "disabled")
            log.info("Upgrade complete.")

    elif args.restore:
        restore = restore_database(args.restore, install_path)
        if not restore:
            exit(1)
