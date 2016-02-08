$( document ).ready(function() {

  firstLevelMenu = $( ".links-top-level" );
  secondLevelMenu = $( ".links-second-level" );
  thirdLevelMenu = $( ".links-third-level" );

});

// For the breadcrumbs
$( document ).on('click', '.menu-first-level', function (e){

  // All this will do is hide the menu levels
  if(thirdLevelMenu.css('display') == 'block' && secondLevelMenu.css('display') == 'block')
  {
    thirdLevelMenu.toggle('slide', { direction: 'left' }, 500);
    secondLevelMenu.delay(500).toggle('slide', { direction: 'left' }, 500, function(){
      secondLevelMenu.empty();
      thirdLevelMenu.empty();
    });
  }
  else if(secondLevelMenu.css('display') == 'block')
  {
    secondLevelMenu.toggle('slide', { direction: 'left' }, 500, function(){
      secondLevelMenu.empty();
    });
  }

});

$( document ).on('click', '.menu-second-level', function (e){
  e.preventDefault();

  if(thirdLevelMenu.toggle('display') == 'block')
  {
    thirdLevelMenu.toggle('slide', { direction: 'left' }, 500, function(){
      thirdLevelMenu.empty();
    });
  }
});

// Level 1 link clicked
$(document).on( 'click', '.links-top-level ul li a', function(e) {

  e.preventDefault();
  $(this).closest('ul').children('.active').removeClass('active');
  $(this).closest('li').addClass('active', 100);

  menuItemId = $(this).data( 'menu-item-id' );
  if(!menuItemId)
  {
    window.location.assign($(this).attr('href'));
    return;
  }

  menuSlug = $(this).data( 'menu-slug' );

  if(thirdLevelMenu.is(':visible') && secondLevelMenu.is(':visible'))
  {
      thirdLevelMenu.toggle('slide', { direction: 'left' }, 500);
      secondLevelMenu.delay(500).toggle('slide', { direction: 'left' }, 500, function(){

      secondLevelMenu.empty();
      thirdLevelMenu.empty();

      getSecondLevelMenu(menuSlug, menuItemId);

    });
  }
  else if(secondLevelMenu.is(':visible'))
  {
      secondLevelMenu.toggle('slide', { direction: 'left' }, 500, function (){

      secondLevelMenu.empty();
      getSecondLevelMenu(menuSlug, menuItemId);

    });

  }
  else
  {
    getSecondLevelMenu(menuSlug, menuItemId);
  }

});

function getSecondLevelMenu(menuSlug, menuItemId)
{
  $.ajax({
    type: "get",
    dataType: "html",
    data: { menu_item_id: menuItemId, menu: menuSlug, menu_level: 'level_two', action: 'get_menu_level' },
    url: wp_js_object.ajax_url,
    success: function (data){
        // Add to div
        var menuHtml = $('<ul>').html(data);
        secondLevelMenu.append(menuHtml);
        secondLevelMenu.toggle('slide', { direction: 'left' }, 500);
        return;
    }
  });
}

// Level 2 link clicked
$(document).on( 'click', '.links-second-level ul li a', function(e) {

  e.preventDefault();
  // $(this).siblings().removeClass('active');
  // $(this).addClass('active', 1000);
  $(this).closest('ul').children('.active').removeClass('active');
  $(this).closest('li').addClass('active', 100);

  menuItemId = $(this).data( 'menu-item-id' );
  if(!menuItemId)
  {
    window.location.assign($(this).attr('href'));
    return;
  }

  menuSlug = $(this).data( 'menu-slug' );

  if(thirdLevelMenu.is(':visible'))
  {
    thirdLevelMenu.toggle('slide', { direction: 'left' }, 500, function(){
      thirdLevelMenu.empty();
      toggleThirdLevel(menuSlug, menuItemId);
    });
  }
  else
  {
    toggleThirdLevel(menuSlug, menuItemId);
  }

});

function toggleThirdLevel(menuSlug, menuItemId)
{
  $.ajax({
    type: "get",
    dataType: "html",
    data: { menu_item_id: menuItemId, menu: menuSlug, menu_level: 'level_three', action: 'get_menu_level' },
    url: wp_js_object.ajax_url,
    success: function (data){
        // Add to div
        var ulContainer = '<ul id="mdddenu-'+menuSlug+'" clas="menu">';
        var menuHtml = $(ulContainer).html(data);
        thirdLevelMenu.append(menuHtml);
        $( window ).width() < 768 ? secondLevelMenu.toggle('slide', { direction: 'left' }, 500) : "";
        thirdLevelMenu.delay(500).toggle('slide', { direction: 'left' }, 500);
        return;
    }
  });
}

// Links description text show
$(document).on( 'mouseenter mouseleave', '.links-top-level ul li, .links-second-level ul li, .links-third-level ul li', function() {

  ptag = $(this).children('p');
  if( ptag.length > 0) { ptag.slideToggle(); }

});

//Handler for the window being resized
$( window ).resize(function() {
  if($( window ).width() < 768)
  {
    console.log('small');
    if(thirdLevelMenu.css('display') == 'block')
    {
      secondLevelMenu.css( "display", "none" );
      console.log('hide');
    }

  }
  else
  {
    if(thirdLevelMenu.css('display') == 'block')
    {
      secondLevelMenu.css( "display", "block" );
      console.log('show');
    }
    console.log('big');
  }
});
