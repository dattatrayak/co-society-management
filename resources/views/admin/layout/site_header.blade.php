<div class="site-header-wrap">
    <?php $menu = getBreadCrumData(); ?>
    <h1 class="mt-4">{{ (isset($menu[0]->page_heading)) ?  $menu[0]->page_heading :  $menu[0]->name }}</h1>
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item">Home</li>
        @foreach (array_reverse( $menu) as $menu)
            <li class="breadcrumb-item"><a href="{{ url('/')."/".$menu->url }}" >{{ $menu->name }}</a></li>
        @endforeach
    </ol>
</div>
