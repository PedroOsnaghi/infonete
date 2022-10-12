{{> header}}
<div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px" id="band">
    <h2 class="w3-wide">Canciones</h2>
    <table class="w3-table">
        <tr>
            <th>Cancion</th>
            <th>duracion</th>
            <th>reproducir</th>
        </tr>

        {{#canciones}}
        <tr>
            <td>{{nombre}}</td>
            <td>{{duracion}}</td>
            <td> <a href="/song/description/id={{idCancion}}">Ver</a> </td>
        </tr>
        {{/canciones}}

    </table>
</div>
{{> footer}}