window.onload = function() {
  window.ui = SwaggerUIBundle({
    url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/openapi.yml",
    dom_id: '#swagger-ui',
    deepLinking: true,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset.slice(1),
    ],
    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],
    layout: "StandaloneLayout"
  });
};
