Messages module
==============

Provides client messages which can come from report_broken_client or other scripts

The table provides the following information:

* type (string) Use one of 'danger', 'warning', 'info' or 'success'
* module (string) The name of the reporting module
* msg (string) A (short) message
* timestamp (int) UNIX timestamp

Remarks
---

There's no client component to this module, it should be invoked by other modules.