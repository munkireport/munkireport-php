<style>svg{height: 400px; width:100%;}</style>

<h2><span data-i18n="servermetrics.memory.usage"></span></h2>
<svg id="memory-usage"></svg>

<h2 data-i18n="servermetrics.cpu_usage"></h2>
<svg id="cpu-usage"></svg>

<h2 data-i18n="servermetrics.network_traffic"></h2>
<svg id="network-traffic"></svg>

<h2 data-i18n="servermetrics.memory.pressure"></h2>
<svg id="memory-pressure"></svg>

<h2 data-i18n="servermetrics.caching.bytes_served"></h2>
<svg id="caching-bytes-served"></svg>

<h2 data-i18n="servermetrics.sharing_connected_users"></h2>
<svg id="sharing-connected-users"></svg>

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.serverstats.js"></script>
