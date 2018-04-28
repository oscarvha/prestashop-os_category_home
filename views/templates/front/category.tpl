

<article class="home-categories__item">
    <div class="home-categories__wrapper">


    <figure class="home-categories__image">
        <a href="">
            <img src="{$link->getCatSecondaryImageLink('secondary_img', $category->id, 'slider_home' )}" alt="">

        </a>
        {*{assign var=id_category value=$category_id}*}


    </figure>
        <div class="home-category__link-content">
            <a href="{$link->getCategoryLink($category->id)}" class="btn btn--primary home-categories__link">
                {$category->name[$lang]}
            </a>
        </div>
    </div>

</article>