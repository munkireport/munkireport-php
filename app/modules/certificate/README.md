Certificate module
==================

Provides certificate information on certs found in `/etc/certificates/` and the System Keychain.

The table provides the following information per 'certificate':

* id (int) Unique id
* serial_number (string) Serial Number
* cert_exp_time (int) Unix timestamp of expiration time
* cert_path (string) Path to certificate
* cert_cn (string) Common name
* timestamp (int) Timestamp of last update
* issuer (string) Who issued the certificate
* cert_location (string) Where the certificate is stored

