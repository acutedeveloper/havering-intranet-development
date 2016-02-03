$(document).ready(function(){

  topLevel = $('.links-top-level' );
  secondLevel = $( '.links-second-level' );
  thirdLevel = $( '.links-third-level' );

  // Event functions
  $(document).on('click', '.links-top-level li a', function(e){

    e.preventDefault();
    $(this).siblings().removeClass('active');
    $(this).addClass('active', 100);
    menuItemId = $(this).data( 'menu-item-id' );
    if(!menuItemId)
    {
      window.location.assign($(this).attr('href'));
      return;
    }

    $( '.links-second-level' ).remove();
    $( '.links-third-level' ).remove();
    menuSlug = $(this).data( 'menu-slug' );

    buildSecondLevel(menuSlug, menuItemId);

  });

  $(document).on('click', '.links-second-level li a', function(e){

    e.preventDefault();
    $(this).siblings().removeClass('active');
    $(this).addClass('active', 1000);

    menuItemId = $(this).data( 'menu-item-id' );
    if(!menuItemId)
    {
      window.location.assign($(this).attr('href'));
      return;
    }
    menuSlug = $(this).data( 'menu-slug' );
    $( '.links-third-level' ).remove();
    //revealThirdLevel();
    buildThirdLevel(menuSlug, menuItemId);

  });

  // Links description text show
  $('.links-second-level li').bind('mouseenter mouseleave ', function(){
    console.log("Hovered");
    ptag = $(this).children('p');
    if( ptag.length > 0) { ptag.slideToggle(); }

  });

  //Handler for the window being resized
  $( window ).resize(function() {
    if($( window ).width() < 768)
    {
      if(secondLevel.hasClass('display-desktop-300'))
      {
        secondLevel.removeClass('display-desktop-300').addClass('mobile-showing').css({"left": "0"});
      }
      if(thirdLevel.hasClass('display-desktop-600'))
      {
        thirdLevel.removeClass('display-desktop-600').addClass('mobile-showing').css({"left": "0"});
      }
      else if(thirdLevel.hasClass('display-desktop-300'))
      {
        thirdLevel.removeClass('display-desktop-300');
      }
    }
    else
    {
      if(secondLevel.hasClass('mobile-showing'))
      {
        secondLevel.removeAttr('style').removeClass('mobile-showing').addClass('display-desktop-300');
      }
      if(thirdLevel.hasClass('mobile-showing'))
      {
        thirdLevel.removeAttr('style').removeClass('mobile-showing').addClass('display-desktop-600');
      }

    }
  });

  function revealSecondLevel()
  {
    if( $(window).width() > 768 )
    {
      secondLevel.addClass( 'display-desktop-300', 300 );
      thirdLevel.addClass( 'display-desktop-300', 300 );
    }
    else
    {
      topLevel.animate({ opacity: "0%" }, "fast");
      secondLevel.addClass('mobile-showing').animate({ left: "0", opacity: "100%" }, "fast");
    }
  }

  function buildSecondLevel(menuSlug, menuItemId)
  {
    $.ajax({
      type: "get",
      dataType: "html",
      data: { menu_item_id: menuItemId, menu: menuSlug, menu_level: 'level_two', action: 'get_menu_level' },
      url: wp_js_object.ajax_url,
      success: function(response){
          newSecondLevelDiv = $('<div class="links-second-level display-desktop-300"></div>').html(response);
          $('#content-navigation').append(newSecondLevelDiv);
          revealSecondLevel();
        }
    });

  }

  function buildThirdLevel(menuSlug, menuItemId)
  {
    $.ajax({
      type: "get",
      dataType: "html",
      data: { menu_item_id: menuItemId, menu: menuSlug, menu_level: 'level_three', action: 'get_menu_level' },
      url: wp_js_object.ajax_url,
      success: function(response){
          newThirdLevelDiv = $('<div class="links-third-level display-desktop-600"></div>').html(response);
          $('#content-navigation').append(newThirdLevelDiv);
          revealThirdLevel();
        }
    });

  }

  function revealThirdLevel()
  {
    if( $(window).width() > 768 )
    {
      thirdLevel.addClass( 'display-desktop-600', 300);
    }
    else
    {
      secondLevel.animate({ opacity: "0%" }, "fast");
      thirdLevel.addClass('mobile-showing').animate({ left: "0", opacity: "100%" }, "fast");
    }
  }

});
