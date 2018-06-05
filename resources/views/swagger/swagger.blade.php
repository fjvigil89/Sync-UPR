<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Doc Sync</title>    
    <link rel="stylesheet" type="image/png" href="/css/fonts/fonts.googleapis.css" />
    <link rel="stylesheet" type="text/css" href="/swagger/swagger-ui.css" >
    
    <link rel="icon" href="./images/favicon.ico">
    <style>
      html
      {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
      }

      *,
      *:before,
      *:after
      {
        box-sizing: inherit;
      }

      body
      {
        margin:0;
        background: #fafafa;
      }
    </style>
  </head>

  <body>
    <div id="swagger-ui"></div>
      
    <script src="/swagger/swagger-ui-bundle.js"> </script>
    <script src="/swagger/swagger-ui-standalone-preset.js"> </script>    
    <script>
    window.onload = function() {

      // Build a system
      const ui = SwaggerUIBundle({
        url: "https://sync.upr.edu.cu/api/swagger.json",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],
        plugins: [
          SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: "StandaloneLayout"
      })

      window.ui = ui
    }
  </script>
  </body>
</html>
