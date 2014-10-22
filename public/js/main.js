(function() {
  $(function() {
    var url;
    url = window.location.pathname;
    console.log(url);
    return $('nav a').each(function() {
      if ($(this).attr('href') === url) {
        return $(this).addClass('active');
      } else {
        return $('#nav li').first(function() {
          return a.addClass('active');
        });
      }
    });
  });

  $(window).scroll(function() {
    if ($(this).scrollTop()) {
      return $("#toTop").fadeIn();
    } else {
      return $("#toTop").fadeOut();
    }
  });

  $("#toTop").click(function() {
    return $("html, body").animate({
      scrollTop: 0
    }, 1000);
  });

  $(function() {
    var aside, section;
    aside = $('#asideResult');
    section = $('#sectionResult');
    return $(window).scroll(function() {
      if ($(this).scrollTop() > 100) {
        aside.css({
          'width': '222px',
          'position': 'fixed',
          'top': '55px'
        });
        return section.css({
          'width': '725px',
          'position': 'relative',
          'float': 'right'
        });
      } else {
        aside.removeAttr('style');
        return section.removeAttr('style');
      }
    });
  });

  $(function() {
    $('#topFrontMenu .loginform').hide();
    return $('#topFrontMenu>.first').click(function(e) {
      $('#topFrontMenu .loginform').slideToggle();
      return e.preventDefault();
    });
  });

  $(function() {
    $('#respmenu').click(function(e) {
      $('ul#menu').slideToggle();
      return e.preventDefault();
    });
  });

  $(function() {
    $('#login button').click(function(e) {
      return $('#login section.loginform').slideToggle();
    });
  });

  $("dl.slide dt").on("click", function(event) {
    $(this).next().slideToggle();
    return console.log($(this));
  });

  $(function() {
    var kontab, navtab, tab;
    $('#sliderwrap').mouseover(function() {
      $('#prevBtn').css('opacity', '0.9');
      return $('#nextBtn').css('opacity', '0.9');
    }).mouseleave(function() {
      $('#prevBtn').css('opacity', '0.1');
      return $('#nextBtn').css('opacity', '0.1');
    });
    tab = $('.tab');
    navtab = tab.children('ul').children('li');
    kontab = tab.children('section').children('article');
    navtab.first().addClass('aktif');
    kontab.hide();
    kontab.first().show();
    return navtab.on('click', function(event) {
      $('.kon' + $(this).attr('class')).slideDown();
      $('.kon' + $(this).attr('class')).siblings().slideUp();
      navtab.removeClass('aktif');
      return $(this).addClass('aktif');
    });
  });

  $('#slider').easySlider({
    auto: true,
    continuous: true
  });

  (function($) {
    return $('.upload').on('click', function() {
      if ($(this).children('button').text() === 'add') {
        $('#uploadavatar').slideDown();
        return $(this).children('button').text('cancel');
      } else {
        $(this).children('button').text('add');
        return $('#uploadavatar').slideUp();
      }
    });
  })(jQuery);

  $('.bisatutup').append($("<span class='tutup'>x</span>"));

  $('span.tutup').on('click', function() {
    return $(this).parent().fadeOut();
  });

  $(function() {
    $("#PostalPrefix").keyup(function() {
      var nilai, strOptionID;
      if ($(this).val().length === 2) {
        strOptionID = "PostalPrefixDistrict";
        nilai = strOptionID + $("#PostalPrefix").val();
        return $("#District").find("option#" + nilai).prop("selected", true);
      }
    });
  });

  $(function() {
    var lev;
    lev = $('.level');
    return $('#level').change(function() {
      var sel;
      sel = $(this).val();
      if (sel === '') {
        lev.hide();
        return alert("Please select your level!");
      } else {
        lev.hide();
        return $('.level#' + sel).show();
      }
    });
  });

  $(function() {
    var unslider;
    unslider = $('.wrapslide').unslider();
    return $(".unslider-arrow").click(function(e) {
      var fn;
      fn = this.className.split(" ")[1];
      unslider.data("unslider")[fn]();
      e.preventDefault();
    });
  });

  $('#datepicker').datepicker({
    inline: true
  }, {
    showOtherMonths: true,
    dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
  });

  $('form.validasi').validate({
    rules: {
      name: "required",
      password: "required",
      repassword: {
        required: true,
        equalTo: "#password"
      },
      gender: {
        required: true,
        minlength: 1
      },
      email: {
        required: true,
        email: true
      }
    },
    messages: {
      name: "Please specify your name",
      email: {
        required: "We need your email address to contact you",
        email: "Your email address must be in the format of name@domain.com"
      }
    }
  });

}).call(this);
