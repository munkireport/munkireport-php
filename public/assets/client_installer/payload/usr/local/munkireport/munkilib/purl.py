#!/usr/local/munkireport/python3
# encoding: utf-8
#
# Copyright 2009-2014 Greg Neagle.
#
# Licensed under the Apache License, Version 2.0 (the 'License');
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#      http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an 'AS IS' BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#
# Adaptation of purl.py by Michael Lynn
# https://gist.github.com/pudquick/a73d0ce7cd8730c97491
"""purl.py.

Created by Greg Neagle on 2013-11-21.
Modified by Arjen van Bochoven on 2015-09-23

curl replacement using NSURLConnection and friends
"""

# builtin super doesn't work with Cocoa classes in recent PyObjC releases.
from objc import super

# PyLint cannot properly find names inside Cocoa libraries, so issues bogus
# No name 'Foo' in module 'Bar' warnings. Disable them.
# pylint: disable=E0611
from Foundation import NSBundle
from Foundation import NSRunLoop, NSDate
from Foundation import NSObject, NSURL, NSURLConnection
from Foundation import NSMutableURLRequest
from Foundation import NSURLRequestReloadIgnoringLocalCacheData
from Foundation import NSLog
from Foundation import NSURLCredential, NSURLCredentialPersistenceNone
from Foundation import NSData, NSString

# pylint: enable=E0611

# Disable PyLint complaining about 'invalid' names
# pylint: disable=C0103


# disturbing hack warning!
# this works around an issue with App Transport Security on 10.11
bundle = NSBundle.mainBundle()
info = bundle.localizedInfoDictionary() or bundle.infoDictionary()
info["NSAppTransportSecurity"] = {"NSAllowsArbitraryLoads": True}


ssl_error_codes = {
    -9800: "SSL protocol error",
    -9801: "Cipher Suite negotiation failure",
    -9802: "Fatal alert",
    -9803: "I/O would block (not fatal)",
    -9804: "Attempt to restore an unknown session",
    -9805: "Connection closed gracefully",
    -9806: "Connection closed via error",
    -9807: "Invalid certificate chain",
    -9808: "Bad certificate format",
    -9809: "Underlying cryptographic error",
    -9810: "Internal error",
    -9811: "Module attach failure",
    -9812: "Valid cert chain, untrusted root",
    -9813: "Cert chain not verified by root",
    -9814: "Chain had an expired cert",
    -9815: "Chain had a cert not yet valid",
    -9816: "Server closed session with no notification",
    -9817: "Insufficient buffer provided",
    -9818: "Bad SSLCipherSuite",
    -9819: "Unexpected message received",
    -9820: "Bad MAC",
    -9821: "Decryption failed",
    -9822: "Record overflow",
    -9823: "Decompression failure",
    -9824: "Handshake failure",
    -9825: "Misc. bad certificate",
    -9826: "Bad unsupported cert format",
    -9827: "Certificate revoked",
    -9828: "Certificate expired",
    -9829: "Unknown certificate",
    -9830: "Illegal parameter",
    -9831: "Unknown Cert Authority",
    -9832: "Access denied",
    -9833: "Decoding error",
    -9834: "Decryption error",
    -9835: "Export restriction",
    -9836: "Bad protocol version",
    -9837: "Insufficient security",
    -9838: "Internal error",
    -9839: "User canceled",
    -9840: "No renegotiation allowed",
    -9841: "Peer cert is valid, or was ignored if verification disabled",
    -9842: "Server has requested a client cert",
    -9843: "Peer host name mismatch",
    -9844: "Peer dropped connection before responding",
    -9845: "Decryption failure",
    -9846: "Bad MAC",
    -9847: "Record overflow",
    -9848: "Configuration error",
    -9849: "Unexpected (skipped) record in DTLS",
}


class Purl(NSObject):
    """A class for getting content from a URL using NSURLConnection and
    friends."""

    # since we inherit from NSObject, PyLint issues a few bogus warnings
    # pylint: disable=W0232,E1002

    # Don't want to define the attributes twice that are initialized in
    # initWithOptions_(), so:
    # pylint: disable=E1101,W0201

    def initWithOptions_(self, options):
        """Set up our Purl object."""
        self = super(Purl, self).init()
        if not self:
            return

        self.follow_redirects = options.get("follow_redirects", False)
        self.url = options.get("url")
        self.method = options.get("method", "GET")
        self.additional_headers = options.get("additional_headers", {})
        self.username = options.get("username")
        self.password = options.get("password")
        self.connection_timeout = options.get("connection_timeout", 10)
        if options.get("content_type") is not None:
            self.additional_headers["Content-Type"] = options.get("content_type")
        self.body = options.get("body")
        self.response_data = ""

        self.log = options.get("logging_function", NSLog)

        self.response = None
        self.headers = None
        self.status = None
        self.error = None
        self.SSLerror = None
        self.done = False
        self.redirection = []
        self.connection = None
        return self

    def start(self):
        """Start the connection."""
        url = NSURL.URLWithString_(self.url)
        request = NSMutableURLRequest.requestWithURL_cachePolicy_timeoutInterval_(
            url, NSURLRequestReloadIgnoringLocalCacheData, self.connection_timeout
        )
        if self.additional_headers:
            for header, value in list(self.additional_headers.items()):
                request.setValue_forHTTPHeaderField_(value, header)
        request.setHTTPMethod_(self.method)

        if self.method == "POST":
            body_unicode = str(self.body)
            body_data = NSData.dataWithBytes_length_(
                NSString.stringWithString_(body_unicode).UTF8String(),
                len(body_unicode.encode("utf-8")),
            )
            request.setHTTPBody_(body_data)

        self.connection = NSURLConnection.alloc().initWithRequest_delegate_(
            request, self
        )

    def cancel(self):
        """Cancel the connection."""
        if self.connection:
            self.connection.cancel()
            self.done = True

    def isDone(self):
        """Check if the connection request is complete.

        As a side effect, allow the delegates to work my letting the run
        loop run for a bit
        """
        if self.done:
            return self.done
        # let the delegates do their thing
        NSRunLoop.currentRunLoop().runUntilDate_(
            NSDate.dateWithTimeIntervalSinceNow_(0.1)
        )
        return self.done

    def get_response_data(self):
        """Return response data."""
        return self.response_data

    def connection_didFailWithError_(self, connection, error):
        """NSURLConnection delegate method Sent when a connection fails to load
        its request successfully."""

        # we don't actually use the connection argument, so
        # pylint: disable=W0613

        self.error = error
        # If this was an SSL error, try to extract the SSL error code.
        if "NSUnderlyingError" in error.userInfo():
            ssl_code = (
                error.userInfo()["NSUnderlyingError"]
                .userInfo()
                .get("_kCFNetworkCFStreamSSLErrorOriginalValue", None)
            )
            if ssl_code:
                self.SSLerror = (
                    ssl_code,
                    ssl_error_codes.get(ssl_code, "Unknown SSL error"),
                )
        self.done = True

    def connectionDidFinishLoading_(self, connection):
        """NSURLConnectionDataDelegat delegate method Sent when a connection
        has finished loading successfully."""

        # we don't actually use the connection argument, so
        # pylint: disable=W0613

        self.done = True

    def connection_didReceiveResponse_(self, connection, response):
        """NSURLConnectionDataDelegate delegate method Sent when the connection
        has received sufficient data to construct the URL response for its
        request."""

        # we don't actually use the connection argument, so
        # pylint: disable=W0613

        self.response = response

        if response.className() == "NSHTTPURLResponse":
            # Headers and status code only available for HTTP/S transfers
            self.status = response.statusCode()
            self.headers = dict(response.allHeaderFields())

    def connection_willSendRequest_redirectResponse_(
        self, connection, request, response
    ):
        """NSURLConnectionDataDelegate delegate method Sent when the connection
        determines that it must change URLs in order to continue loading a
        request."""

        # we don't actually use the connection argument, so
        # pylint: disable=W0613

        if response == None:
            # This isn't a real redirect, this is without talking to a server.
            # Pass it back as-is
            return request
        # But if we're here, it appears to be a real redirect attempt
        # Annoyingly, we apparently can't get access to the headers from the
        # site that told us to redirect. All we know is that we were told
        # to redirect and where the new location is.
        newURL = request.URL().absoluteString()
        self.redirection.append([newURL, dict(response.allHeaderFields())])
        if self.follow_redirects:
            # Allow the redirect
            self.log("Allowing redirect to: %s" % newURL)
            return request
        else:
            # Deny the redirect
            self.log("Denying redirect to: %s" % newURL)
            return None

    def connection_willSendRequestForAuthenticationChallenge_(
        self, connection, challenge
    ):
        """NSURLConnection delegate method Tells the delegate that the
        connection will send a request for an authentication challenge.

        New in 10.7.
        """

        # we don't actually use the connection argument, so
        # pylint: disable=W0613

        self.log("connection_willSendRequestForAuthenticationChallenge_")
        protectionSpace = challenge.protectionSpace()
        host = protectionSpace.host()
        realm = protectionSpace.realm()
        authenticationMethod = protectionSpace.authenticationMethod()
        self.log(
            "Authentication challenge for Host: %s Realm: %s AuthMethod: %s"
            % (host, realm, authenticationMethod)
        )
        if challenge.previousFailureCount() > 0:
            # we have the wrong credentials. just fail
            self.log("Previous authentication attempt failed.")
            challenge.sender().cancelAuthenticationChallenge_(challenge)
        if (
            self.username
            and self.password
            and authenticationMethod
            in [
                "NSURLAuthenticationMethodDefault",
                "NSURLAuthenticationMethodHTTPBasic",
                "NSURLAuthenticationMethodHTTPDigest",
            ]
        ):
            self.log("Will attempt to authenticate.")
            self.log(
                "Username: %s Password: %s"
                % (self.username, ("*" * len(self.password or "")))
            )
            credential = NSURLCredential.credentialWithUser_password_persistence_(
                self.username, self.password, NSURLCredentialPersistenceNone
            )
            challenge.sender().useCredential_forAuthenticationChallenge_(
                credential, challenge
            )
        else:
            # fall back to system-provided default behavior
            self.log("Allowing OS to handle authentication request")
            challenge.sender().performDefaultHandlingForAuthenticationChallenge_(
                challenge
            )

    def connection_canAuthenticateAgainstProtectionSpace_(
        self, connection, protectionSpace
    ):
        """NSURLConnection delegate method Sent to determine whether the
        delegate is able to respond to a protection space’s form of
        authentication.

        Deprecated in 10.10
        """

        # we don't actually use the connection argument, so
        # pylint: disable=W0613

        # this is not called in 10.5.x.
        self.log("connection_canAuthenticateAgainstProtectionSpace_")
        if protectionSpace:
            host = protectionSpace.host()
            realm = protectionSpace.realm()
            authenticationMethod = protectionSpace.authenticationMethod()
            self.log(
                "Protection space found. Host: %s Realm: %s AuthMethod: %s"
                % (host, realm, authenticationMethod)
            )
            if (
                self.username
                and self.password
                and authenticationMethod
                in [
                    "NSURLAuthenticationMethodDefault",
                    "NSURLAuthenticationMethodHTTPBasic",
                    "NSURLAuthenticationMethodHTTPDigest",
                ]
            ):
                # we know how to handle this
                self.log("Can handle this authentication request")
                return True
        # we don't know how to handle this; let the OS try
        self.log("Allowing OS to handle authentication request")
        return False

    def connection_didReceiveAuthenticationChallenge_(self, connection, challenge):
        """NSURLConnection delegate method Sent when a connection must
        authenticate a challenge in order to download its request.

        Deprecated in 10.10
        """

        # we don't actually use the connection argument, so
        # pylint: disable=W0613

        self.log("connection_didReceiveAuthenticationChallenge_")
        protectionSpace = challenge.protectionSpace()
        host = protectionSpace.host()
        realm = protectionSpace.realm()
        authenticationMethod = protectionSpace.authenticationMethod()
        self.log(
            "Authentication challenge for Host: %s Realm: %s AuthMethod: %s"
            % (host, realm, authenticationMethod)
        )
        if challenge.previousFailureCount() > 0:
            # we have the wrong credentials. just fail
            self.log("Previous authentication attempt failed.")
            challenge.sender().cancelAuthenticationChallenge_(challenge)
        if (
            self.username
            and self.password
            and authenticationMethod
            in [
                "NSURLAuthenticationMethodDefault",
                "NSURLAuthenticationMethodHTTPBasic",
                "NSURLAuthenticationMethodHTTPDigest",
            ]
        ):
            self.log("Will attempt to authenticate.")
            self.log(
                "Username: %s Password: %s"
                % (self.username, ("*" * len(self.password or "")))
            )
            credential = NSURLCredential.credentialWithUser_password_persistence_(
                self.username, self.password, NSURLCredentialPersistenceNone
            )
            challenge.sender().useCredential_forAuthenticationChallenge_(
                credential, challenge
            )
        else:
            # fall back to system-provided default behavior
            self.log("Continuing without credential.")
            challenge.sender().continueWithoutCredentialForAuthenticationChallenge_(
                challenge
            )

    def connection_didReceiveData_(self, connection, data):
        """NSURLConnectionDataDelegate method Sent as a connection loads data
        incrementally."""

        # we don't actually use the connection argument, so
        # pylint: disable=W0613
        self.response_data = self.response_data + str(data)
