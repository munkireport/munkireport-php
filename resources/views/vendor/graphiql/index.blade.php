<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset=utf-8/>
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GraphiQL</title>
    <style>
        body {
            height: 100%;
            margin: 0;
            width: 100%;
            overflow: hidden;
        }

        #graphiql {
            height: 100vh;
        }

        /* Make the explorer feel more integrated */
        .docExplorerWrap {
            overflow: auto !important;
            width: 100% !important;
            height: auto !important;
        }
        .doc-explorer-title-bar {
            font-weight: var(--font-weight-medium);
            font-size: var(--font-size-h2);
            overflow-x: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .doc-explorer-rhs {
            display: none;
        }
        .doc-explorer-contents {
            margin: var(--px-16) 0 0;
        }
        .graphiql-explorer-actions select {
            margin-left: var(--px-12);
        }
    </style>
    <script src="{{ \MLL\GraphiQL\DownloadAssetsCommand::reactPath() }}"></script>
    <script src="{{ \MLL\GraphiQL\DownloadAssetsCommand::reactDOMPath() }}"></script>
    <link rel="stylesheet" href="{{ \MLL\GraphiQL\DownloadAssetsCommand::cssPath() }}"/>
    <link rel="shortcut icon" href="{{ \MLL\GraphiQL\DownloadAssetsCommand::faviconPath() }}"/>
</head>

<body>

<div id="graphiql">Loading...</div>
<script src="{{ \MLL\GraphiQL\DownloadAssetsCommand::jsPath() }}"></script>
<script src="{{ \MLL\GraphiQL\DownloadAssetsCommand::explorerPluginPath() }}"></script>
<script>
    const fetcher = GraphiQL.createFetcher({
        url: '{{ $url }}',
        subscriptionUrl: '{{ $subscriptionUrl }}',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });

    function GraphiQLWithExplorer() {
        const [query, setQuery] = React.useState('');

        return React.createElement(GraphiQL, {
            fetcher: fetcher,
            query: query,
            onEditQuery: setQuery,
            defaultEditorToolsVisibility: true,
            plugins: [
                GraphiQLPluginExplorer.useExplorerPlugin({
                    query: query,
                    onEdit: setQuery,
                })
            ],
        });
    }

    ReactDOM.render(
        React.createElement(GraphiQLWithExplorer),
        document.getElementById('graphiql'),
    );
</script>

</body>
</html>
