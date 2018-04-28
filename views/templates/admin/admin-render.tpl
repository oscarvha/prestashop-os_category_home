
<div class="panel col-md-6">



    <h3>{l s="Products en la Home"}</h3>
    <table class="table">
        <tr>
            <th>{l s="Posicion"}</th>
            <th>{l s="Nombre"}</th>
            <th>{l s="Acciones"}</th>
        </tr>

        {assign var="count" value=0}
        {foreach $categoriesHome  as $category}
            <tr>
                <td>
                    {$positions[$count]}
                </td>
                <td>
                    {$category->name[$lang]}
                </td>
                <td>
                    <a href="{$link->getAdminLink('AdminModules')}&configure=os_category_home&deleteCategory={$category->id}">{l s="Borrar"}</a>
                    <a class="js-change-position" href="{$link->getAdminLink('AdminModules')}&configure=os_category_home&changePosition={$category->id}&positionDestininy=">{l s="Subir posición"}</a>


                </td>


            </tr>
            {$count = $count + 1}
        {/foreach}
    </table>


<script>
    $(document).ready(function(){

        $('.js-change-position').click(function(event){

            event.preventDefault();

            var selectPosition = $('#position-destiny').find(":selected").val();
            var url =  $(this).attr('href')+selectPosition;

            location.href = url;




        });

    });
</script>
    <div>
        {assign var="count" value=0}

        <span>{l s="Posición destino"}</span>
        <select id="position-destiny">
            {foreach $categoriesHome as $posicion}
                {$count = $count+1}
                <option value="{$count}">{$count}</option>
            {/foreach}
        </select>
    </div>
</div>
<div class="panel col-md-6">

    <h3>{l s="Productos Disponibles"}</h3>
    <table class="table">
        <tr>
            <th>{l s="ID"}</th>
            <th>{l s="Nombre"}</th>
            <th>{l s="Acciones"}</th>
        </tr>
{foreach $categories  as $category}
    <tr>
        <td>
            {$category['id_category']}
        </td>
        <td>
            {$category['name']}
        </td>
        <td>
            <a href="{$link->getAdminLink('AdminModules')}&configure=os_category_home&addCategory={$category['id_category']}">{l s="Añadir Categoria"}</a>
        </td>

    </tr>
    {/foreach}
    </table>

</div>




{*</div>*}