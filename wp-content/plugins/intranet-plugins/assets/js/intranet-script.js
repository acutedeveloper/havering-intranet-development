$( document ).ready(function() {

  firstLevelMenu = $( ".links-top-level" );
  secondLevelMenu = $( ".links-second-level" );
  thirdLevelMenu = $( ".links-third-level" );

});

function inactive(e){
  e.preventDefault();
}

// Level 1 link clicked
$(document).on( 'click', '.links-top-level ul li', levelOneClicked );

function levelOneClicked(e){

  e.preventDefault();
  var linkButton = $(this);
  linkButton.closest('ul').children('.active').removeClass('active');
  linkButton.addClass('active', 100);

  // If the link is to a page or external link
  menuItemId = linkButton.find('a').data( 'menu-item-id' );
  if(!menuItemId)
  {
    var linkTarget = linkButton.find('a').attr('target') ? '_blank' : '_self';
    var linkUrl = linkButton.find('a').attr('href');
    window.open(linkUrl, linkTarget);
    return;
  }

  menuSlug = linkButton.find('a').data( 'menu-slug' );

  // This is to disable the double clicks
  $(document).off( 'click', '.links-top-level ul li', levelOneClicked );
  $(document).on( 'click', '.links-top-level ul li', inactive );

  if(thirdLevelMenu.is(':visible') && secondLevelMenu.is(':visible'))
  {
    thirdLevelMenu.toggle('slide', { direction: 'left' }, 500);
    secondLevelMenu.delay(500).toggle('slide', { direction: 'left' }, 500, function(){

      secondLevelMenu.empty();
      thirdLevelMenu.empty();
      remove_crumb('li.menu-second-level');
      remove_crumb('li.menu-third-level');

      toggleSecondLevelMenu(menuSlug, menuItemId);

    });
  }
  else if(secondLevelMenu.is(':visible'))
  {
      secondLevelMenu.toggle('slide', { direction: 'left' }, 500, function (){
      secondLevelMenu.empty();
      remove_crumb('li.menu-second-level');
      toggleSecondLevelMenu(menuSlug, menuItemId);
    });

  }
  else
  {
    remove_crumb('li.menu-second-level');
    toggleSecondLevelMenu(menuSlug, menuItemId);
  }

}

function toggleSecondLevelMenu(menuSlug, menuItemId)
{

  $.ajax({
    type: "get",
    dataType: "html",
    data: { menu_item_id: menuItemId, menu: menuSlug, menu_level: 'level_two', action: 'get_menu_level' },
    url: wp_js_object.ajax_url,
    success: function (data){
        // Add to div
        var menuHtml = $('<ul>').html(data);
        if ( secondLevelMenu.empty() ) {
          secondLevelMenu.append(menuHtml);
          secondLevelMenu.toggle('slide', { direction: 'left' }, 500);
        }
        $(document).off( 'click', '.links-top-level ul li', inactive );
        $(document).on( 'click', '.links-top-level ul li', levelOneClicked );
        return;
    }
  });
}

// Level 2 link clicked
$(document).on( 'click', '.links-second-level ul li a', levelTwoClicked );

function levelTwoClicked(e) {

  e.preventDefault();
  $(this).closest('ul').children('.active').removeClass('active');
  $(this).closest('li').addClass('active', 100);

  // If the link is to a page or external link
  menuItemId = $(this).data( 'menu-item-id' );
  if(!menuItemId)
  {
    var linkTarget = $(this).attr('target') ? '_blank' : '_self';
    var linkUrl = $(this).attr('href');
    window.open(linkUrl, linkTarget);
    return;
  }

  menuSlug = $(this).data( 'menu-slug' );
  menuTax = $(this).data( 'tax-slug' );

  // This is to disable the double clicks
  $(document).off( 'click', '.links-second-level ul li a', levelTwoClicked );
  $(document).on( 'click', '.links-second-level ul li a', inactive );

  if(thirdLevelMenu.is(':visible'))
  {
    thirdLevelMenu.toggle('slide', { direction: 'left' }, 500, function(){
      thirdLevelMenu.empty();
      remove_crumb('li.menu-second-level');
      toggleThirdLevel(menuSlug, menuItemId, menuTax);
    });
  }
  else
  {
    remove_crumb('li.menu-second-level');
    toggleThirdLevel(menuSlug, menuItemId, menuTax);
  }

}

function toggleThirdLevel(menuSlug, menuItemId, menuTax)
{
  $.ajax({
    type: "get",
    dataType: "html",
    data: { menu_item_id: menuItemId, menu: menuSlug, tax: menuTax, menu_level: 'level_three', action: 'get_menu_level' },
    url: wp_js_object.ajax_url,
    success: function (data){
        // Add to div
        var ulContainer = '<ul id="mdddenu-'+menuSlug+'" clas="menu">';
        var menuHtml = $(ulContainer).html(data);
        if ( thirdLevelMenu.empty() ) {
          thirdLevelMenu.append(menuHtml);
          $( window ).width() < 768 ? secondLevelMenu.toggle('slide', { direction: 'left' }, 500) : "";
          thirdLevelMenu.delay(500).toggle('slide', { direction: 'left' }, 500, function(){
            $(document).off( 'click', '.links-second-level ul li a', inactive );
            $(document).on( 'click', '.links-second-level ul li a', levelTwoClicked );
          });
          add_crumb('menu-second-level', menuSlug);
        }
        return;
    }
  });
}

//------ BREADCRUMBS ------//

$(document).on( 'click', '.menu-second-level', function(e) {
  e.preventDefault();

  // Hide the third level
  if(thirdLevelMenu.is(':visible'))
  {
    thirdLevelMenu.toggle('slide', { direction: 'left' }, 500, function(){
      thirdLevelMenu.empty();
      $( window ).width() < 768 ? secondLevelMenu.toggle('slide', { direction: 'left' }, 500) : "";
      remove_crumb('li.menu-second-level');
    });
  }
});

function remove_crumb(level)
{

  $('.breadcrumbs').children( level ).remove();
  return;
}

function add_crumb(level, title)
{
  var random = Math.floor(Math.random() * (100 - 1)) + 1;
  var newCrumb = $('<li>').addClass(level).html('<a href="#">'+ titleCase(title) + '</a>');
  $('.breadcrumbs').append(newCrumb);
  return;
}

function titleCase(str)
{
  var newstr = str.split("-");
  newstr = newstr.join(" ");
  return newstr.charAt(0).toUpperCase() + newstr.slice(1);
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
    if(thirdLevelMenu.css('display') == 'block')
    {
      secondLevelMenu.css( "display", "none" );
    }
  }
  else
  {
    if(thirdLevelMenu.css('display') == 'block')
    {
      secondLevelMenu.css( "display", "block" );
    }
  }
});
