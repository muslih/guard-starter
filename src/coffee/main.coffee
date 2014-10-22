 
$ ->
	url = window.location.pathname
	console.log(url)
	$('nav a').each ->
		if $(@).attr('href') == url
			$(@).addClass('active')
		else
			$('#nav li').first ->
				a.addClass('active')

$(window).scroll ->
  if $(this).scrollTop()
    $("#toTop").fadeIn()
  else
    $("#toTop").fadeOut()

$("#toTop").click ->
  $("html, body").animate
    scrollTop: 0
  , 1000



$ ->
	aside = $('#asideResult')
	section = $('#sectionResult')

	$(window).scroll( -> 
		if $(@).scrollTop() > 100
			aside.css(
				'width': '222px',
				'position': 'fixed'

				'top': '55px'
				# 'left':'0px'
			)
			section.css(
				'width': '725px'
				'position': 'relative'
				'float':'right'
			)
		else
			aside.removeAttr('style')
			section.removeAttr('style')
	)

$ ->
	$('#topFrontMenu .loginform').hide()
	$('#topFrontMenu>.first').click (e)->
		$('#topFrontMenu .loginform').slideToggle()
		e.preventDefault()
$ ->
	$('#respmenu').click (e)->
		$('ul#menu').slideToggle()
		e.preventDefault()
	return

$ ->
	$('#login button').click (e)->
		$('#login section.loginform').slideToggle()
	return
	


# dd = $("dd")
# dt = $("dt")
# dd.filter(":nth-child(n+4)").addClass "sembunyi"
# dt.filter(":first-child").addClass "aktif"
$("dl.slide dt").on "click",(event) ->
	$(this).next().slideToggle()	
	console.log($(@))
	# $(@).addClass "aktif"
	# $(@).siblings("dt").removeClass "aktif"
$ ->
	$('#sliderwrap').mouseover( ->
		$('#prevBtn').css('opacity','0.9')
		$('#nextBtn').css('opacity','0.9')
	).mouseleave(->
		$('#prevBtn').css('opacity','0.1')
		$('#nextBtn').css('opacity','0.1')
	)

	tab = $('.tab')
	navtab = tab.children('ul').children('li')
	kontab = tab.children('section').children('article')

	navtab.first().addClass('aktif')
	kontab.hide()
	kontab.first().show()

	navtab.on 'click', (event) ->
		
			
		$('.kon' + $(@).attr('class')).slideDown()
		$('.kon' + $(@).attr('class')).siblings().slideUp()

		navtab.removeClass('aktif')
		$(@).addClass('aktif')




	# $('#slider-nav').mouseover( ->
	# 	$('#slider-nav').css('opacity','0.9')
	# ).mouseleave(->
	# 	$('#slider-nav').css('opacity','0.1')
	# )

$('#slider').easySlider(
	auto:true,
	continuous: true
)

(($) ->
	$('.upload').on('click', ->

		if $(@).children('button').text() == 'add'
			$('#uploadavatar').slideDown()
			$(@).children('button').text('cancel')
		else
			$(@).children('button').text('add')
			$('#uploadavatar').slideUp()
	)
) jQuery

$('.bisatutup').append($("<span class='tutup'>x</span>"))

$('span.tutup').on('click', ->
		# $(@).parent().css('opacity','0')
		$(@).parent().fadeOut()
)

# fungsi pathname
$ ->
	$("#PostalPrefix").keyup ->
		if $(@).val().length is 2
		    strOptionID = "PostalPrefixDistrict"
		    nilai = strOptionID + $("#PostalPrefix").val()
		    $("#District").find("option#" + nilai).prop "selected", true
	return
# fungsi select level
$ ->
	lev = $('.level')
	$('#level').change ->
		# console.log($(@).val())
		sel = $(@).val();
		if sel is ''
			lev.hide()
			alert "Please select your level!"
		else
			lev.hide()
			$('.level#'+sel).show()


# unslider
$ ->
	unslider = $('.wrapslide').unslider()
	$(".unslider-arrow").click (e) ->
	  fn = @className.split(" ")[1]
	  
	  #  Either do unslider.data('unslider').next() or .prev() depending on the className
	  unslider.data("unslider")[fn]()
	  e.preventDefault()
	  return

$('#datepicker').datepicker(
		inline: true,  
        showOtherMonths: true,  
        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],  
	)

$('form.validasi').validate(
		rules: {
		 name: "required",
		 password: "required",
		 repassword:{
		 	required: true,
		 	equalTo: "#password"
		 }
		 gender:{
		 	required: true,
		 	minlength: 1
		 }
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

	)


