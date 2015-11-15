Event module
==============

Provides client messages which can come from report_broken_client or other scripts

The table provides the following information:

* type (string) Use one of 'danger', 'warning', 'info' or 'success'
* module (string) The name of the reporting module
* msg (string) a localized message identifier (will be rendered by i18n)
* data (string) optional data to add to the message
* timestamp (int) UNIX timestamp

Remarks
---
* Every module can only store one message. So you should only store the most relevant one.
* A newer message will overwrite the previous message.
* The content of the event table will be used to render events in a widget or send notifications

There's no client component to this module, it should be invoked by other modules.