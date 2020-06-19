<?php

return [
    'search_paths' => mr_env('AUTH_LOCAL_SEARCH_PATHS', [local_conf('users')]),
];
