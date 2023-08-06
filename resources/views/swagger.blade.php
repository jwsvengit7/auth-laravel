
<!DOCTYPE html>
<html>
<head>
    <title>API Documentation</title>
    <link href="{{ asset('vendor/swagger-ui/swagger-ui.css') }}" rel="stylesheet">
</head>
<body>
    <div id="swagger-ui"></div>

    <script src="{{ asset('vendor/swagger-ui/swagger-ui-bundle.js') }}"></script>
    <script src="{{ asset('vendor/swagger-ui/swagger-ui-standalone-preset.js') }}"></script>
    <script>
       
        const ui = SwaggerUIBundle({
            url: '{{ asset("docs/api-docs.json") }}',
            dom_id: '#swagger-ui',
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset,
            ],
            layout: "StandaloneLayout",
        });
    </script>
</body>
</html>
