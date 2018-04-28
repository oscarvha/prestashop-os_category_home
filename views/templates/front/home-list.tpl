

<section class="category-home__wrapper ">
    <h3 class="h3 category-home__title ">
        {l s='Una flor para cada ocasión' d='Shop.Theme.Catalog'}
    </h3>

    <div class="categories js-category__slider">

        {foreach from=$categories item="category"}
            {include file="module:os_category_home/views/templates/front/category.tpl" category=$category}
        {/foreach}
    </div>
</section>



<script>
    $(document).ready(function(){
        $('.js-category__slider ').slick({
            centerMode: true,
            infinite: true,
            centerPadding:'50px',
            slidesToShow: 5,
            speed: 500,
            variableWidth: false,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        centerMode: true,
                        infinite: true,
                        dots: false,
                        arrows: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        centerMode: false,
                        arrows:true,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        centerMode: false,
                        arrows: true,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });

</script>

