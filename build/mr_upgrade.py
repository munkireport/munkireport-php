#!/usr/bin/env python3


"""Script for updating MunkiReport"""
import os
import datetime
import subprocess
import json
import urllib
import urllib2
import shutil
import tarfile
import argparse
import logging
import coloredlogs
from distutils.version import LooseVersion
from distutils.dir_util import copy_tree

NOW = datetime.datetime.now()

# log output to console
log = logging.getLogger()
coloredlogs.install(
    fmt="[%(asctime)s] - [%(levelname)-8s] - %(message)s", level="INFO", logger=log
)


class MunkiReportInstall(object):

    """A MunkiReport Install."""
    def __init__(self, install_path):
        self._install_path = os.path.join(install_path,'') or self.install_path
        self._env = self.env_vars
        self._database_type = self.database_type


    @property
    def install_path(self):
        """Return install path"""
        return os.path.dirname(os.path.realpath(__file__)).strip('build')


    @property
    def build_version(self):
        """Return build version"""
        helper = self._install_path + "app/helpers/site_helper.php"
        if os.path.exists(helper):
            with open(helper, "r") as site_helper:
                for line in site_helper:
                    # There is probably a more pythonic way of doing this...
                    if "$GLOBALS['version'] = '" in line:
                        return line.split("'")[3]
        return None


    @property
    def database_type(self):
        """Return database type"""
        if self._env is not None:
            return self._env['CONNECTION_DRIVER'].strip('"') or 'sqlite'
        return None


    @property
    def env_vars(self):
        """Return env vars"""
        env_file_path = self._install_path + '.env'
        if os.path.isfile(env_file_path):
            env_vars = {}
            with open(env_file_path) as env_file:
                for line in env_file:
                    if line.startswith('#') or not line.strip():
                        continue
                    key, value = line.strip().split('=', 1)
                    env_vars[key] = value.strip('"')
            return env_vars
        else:
            return None


    def set_maintenance_mode(self, value):
        """Set maintenance mode to down or remove"""
        if value == "down":
            open(self._install_path + 'storage/framework/' + value, 'a').close()
        else:
            os.remove(self._install_path + 'storage/framework/down')


    def backup_database(self):
        """Backup a MunkiReport database."""
        if self._database_type == "mysql":
            username = self._env['CONNECTION_USERNAME']
            password = self._env['CONNECTION_PASSWORD']
            database = self._env['CONNECTION_DATABASE']
            backup_file = BACKUP_DIR + database + NOW.strftime("%Y%m%d%H%M") + '.bak'
            cmd = "/usr/local/opt/mysql-client/bin/mysqldump" \
                  " --user={} --password={} {} > {}".format(
                      username, password, database, backup_file
                  )
            log.info("Backing up database to '{}'...".format(backup_file))
            #subprocess.Popen(cmd, shell=True)

        elif self._database_type == 'sqlite':
            shutil.copyfile(
                self.install_path + 'app/db/db.sqlite',
                BACKUP_DIR + 'db' + NOW.strftime("%Y%m%d%H%M") + '.sqlite.bak'
            )


    def backup_files(self, install_path):
    """Create file backup of install."""    
        final_dir = BACKUP_DIR + "munkireport" + NOW.strftime("%Y%m%d%H%M")
        log.info(f"Backing up files to '{final_dir}'...")
        os.mkdir(final_dir)
        copy_tree(install_path, final_dir)
       

def github_release_info():
    """Return MR API data"""
    mr_api = "https://api.github.com/repos/munkireport/munkireport-php/releases/latest"
    response = urllib.urlopen(mr_api)
    data = json.loads(response.read())
    return data


def main(info, no_backup, backup_dir, install_path, upgrade, upgrade_version):
    """Main script"""
    munkireport = MunkiReportInstall(install_path)
    install_path = install_path or munkireport.install_path
    build_version = munkireport.build_version
    release_info = github_release_info()

    if not build_version:
        log.error(f"The directory '{install_path}' does not appear to be a valid MunkiReport install.")
        return

    if info:
        log.info(f"Current version: {build_version}")
        log.info(f"GitHub version:  {release_info['tag_name'].strip('v')}")
        log.info(f"Install path:    {install_path}")
        log.info(f"Database type:   {munkireport.database_type}")
        return

    log.info(f"We are at version {build_version}. The latest master version is {release_info['tag_name'].strip('v')}")

    if build_version < release_info['tag_name'].strip('v'):
        log.info(f"Starting upgrade of {install_path}...")
        return
        munkireport.set_maintenance_mode("down")

        # backup database
        munkireport.backup_database(install_path)

        # backup files
        munkireport.backup_files(install_path)

        # Update
        try:
            # do git pull
            log.info("Starting git pull...")
            process = subprocess.Popen(["git", "pull"], stdout=subprocess.PIPE)
            output = process.communicate()[0]
            log.info("Git pull complete.")

            log.info("Running composer...")
            os.chdir(munkireport.install_path)
            process = subprocess.Popen(["/usr/local/bin/composer", "update", "--no-dev"],
                                        stdout=subprocess.PIPE)
            output = process.communicate()[0]
            log.info("Composer complete.")
            os.chdir(munkireport.install_path + "/build/")
        except:
            log.error("Git failed to complete.")

        # Run Migrations
        log.info("Running migrations...")
        migration_file = munkireport.install_path + 'database/migrate.php'
        cmd = f"/usr/bin/php {migration_file}"
        log.debug(f"Command: '{cmd}'")
        proc = subprocess.Popen(cmd, shell=True, stdout=subprocess.PIPE)
        migration_response = proc.stdout.read()
        log.info("Migrations complete.")

        # turn off maintenance mode
        munkireport.set_maintenance_mode("up")

if __name__ == "__main__":
    parser = argparse.ArgumentParser(
            description='Manage a MunkiReport install.')
    group = parser.add_mutually_exclusive_group()
    group.add_argument(
            '-i', '--info',
            action='store_true',
            help='Print info on the MunkiReport install.'
            )
    parser.add_argument(
            '--no-backup',
            help='Do not take any backups before upgrading.',
            default=False
            )
    parser.add_argument(
            '--backup-dir',
            help='Directory to back up to.',
            default='/tmp'
            )
    parser.add_argument(
            '--install-path',
            help='Install path for MunkiReport.',
            default=os.path.dirname(os.path.realpath(__file__)).strip('build')
            )
    parser.add_argument(
            '--upgrade',
            help='Attempt to upgrade MunkiReport',
            default='False'
            )
    parser.add_argument(
            '--upgrade-version',
            help='Version of MunkiReport to upgrade to.',
            default='latest'
            )
    args = parser.parse_args()
    main(
            args.info,
            args.no_backup,
            args.backup_dir,
            args.install_path,
            args.upgrade,
            args.upgrade_version
        )
