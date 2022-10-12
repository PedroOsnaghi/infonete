{{> header}}
<div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px" id="band">
    {{#cancion}}
        <h2 class="w3-wide">{{nombre}}</h2>
            {{duracion}}
    {{/cancion}}
    {{^cancion}}
        Error cancion no encontrada
    {{/cancion}}
</div>
{{> footer}}