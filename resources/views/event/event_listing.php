<?php 

$this->view('listings/default',
[
  "i18n_title" => 'event.event_plural',
  "js_link" => "module/event/js/format_event_data",
  "table" => [
    [
      "column" => "machine.computer_name",
      "i18n_header" => "listing.computername",
      "formatter" => "clientDetail",
    ],
    [
      "column" => "reportdata.serial_number",
      "i18n_header" => "serial",
    ],
    [
      "column" => "reportdata.long_username",
      "i18n_header" => "username",
    ],
    [
      "column" => "event.type",
      "i18n_header" => "type",
    ],
    [
      "column" => "event.module",
      "i18n_header" => "module",
      "formatter" => "eventMessageCombined",
    ],
    [
      "column" => "event.msg",
      "i18n_header" => "message",
    ],
    [
      "column" => "event.data",
      'hide' => true,
    ],
    [
      "column" => "event.timestamp",
      "i18n_header" => "last_seen",
      "formatter" => "timestampToMoment",
      "sort" => "desc",
    ],
  ]
]);
