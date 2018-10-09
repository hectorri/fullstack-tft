{% extends 'layout.twig' %}

{% block content %}
<table>
	<thead>
		<tr>
			<th>Detalle Producto</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{ producto.NOMBRE_PRODUCTO }}</td>
            <td>{{ producto.DESCRIPCION }}</td>
		</tr>
	</tbody>
</table>
<!-- <p><a href="{{ path_for('producto.nuevo', {'idLista': idLista}) }}">Añadir producto</a></p> -->
<p><a href="{{ path_for('lista.misListas') }}">Volver</a></p>
{% endblock %}