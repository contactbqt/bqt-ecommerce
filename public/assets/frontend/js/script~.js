  // Sticky Navbar on Scroll
    $(window).on("scroll", function(){
      if($(this).scrollTop() > 100){
        $("#mainNavbar").addClass("sticky-nav");
      } else {
        $("#mainNavbar").removeClass("sticky-nav");
      }
    });

    // Dropdown on hover (Desktop only)
    $('ul.navbar-nav li.dropdown').hover(function() {
      if($(window).width() > 992){
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(200);
      }
    }, function() {
      if($(window).width() > 992){
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(200);
      }
    });

    // Custom mobile menu toggle
  document.addEventListener("DOMContentLoaded", function() {
    const toggler = document.querySelector(".mobile-toggler");
    const mobileMenu = document.getElementById("mobileMenu");
    const closeBtn = document.querySelector(".mobile-close");

    toggler.addEventListener("click", () => {
      mobileMenu.classList.add("open");
    });

    closeBtn.addEventListener("click", () => {
      mobileMenu.classList.remove("open");
    });

    // Close menu when clicking outside
    document.addEventListener("click", (e) => {
      if (!mobileMenu.contains(e.target) && !toggler.contains(e.target)) {
        mobileMenu.classList.remove("open");
      }
    });

      document.querySelectorAll(".submenu-toggle").forEach(toggle => {
  toggle.addEventListener("click", (e) => {
    e.preventDefault();
    let submenu = toggle.nextElementSibling;
    submenu.classList.toggle("open");
  });
});
 
  });

//Header Ends //



	  // Hero Slider //
	  $('.hero-carousel').owlCarousel({
		loop:true,
		margin:10,
		autoplay:true,
		smartSpeed: 1000,
		autoplay: 4000,
		nav:true,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	});

	  let slides = document.querySelectorAll(".hero-carousel");
    let index = 0;

    function showSlide() {
      slides.forEach((slide, i) => {
        slide.classList.remove("active");
        if (i === index) slide.classList.add("active");
      });
      index = (index + 1) % slides.length;
    }

    setInterval(showSlide, 5000);

	// Carousel //

 // dEPARTMENT SLIDER SECTION //	

	$('.department-slide').owlCarousel({
		loop:true,
		margin:10,
		autoplay:true,
		smartSpeed: 1000,
		autoplay: 4000,
		dots:false,
		nav:true,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:2
			},
			1000:{
				items:4
			}
		}
	});


	// Testimonial slider //

	$('.testimonial-slider').owlCarousel({
		loop:true,
		margin:10,
		dots: false,
		autoplay:true,
		smartSpeed: 1000,
		autoplay: 6000,
		nav:true,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	});
	// Testimonial Slider //

	// Doctors Listing SLider //
	$('.doctors').owlCarousel({
		loop:false,
		margin:10,
		dots:false,
		center:false,
		nav:true,
		navText : [
			'<i class="fa fa-arrow-left"></i>',
			'<i class="fa fa-arrow-right"></i>'
		],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:4
			}
		}
	})
	// Doctors Listing Ends //

	  // Counter Js 
	  const counters = document.querySelectorAll('.counter');
	  const speed = 200; // lower = faster
	
	  const animateCounters = () => {
		counters.forEach(counter => {
		  const updateCount = () => {
			const target = +counter.getAttribute('data-target');
			const count = +counter.innerText;
			const increment = Math.ceil(target / speed);
	
			if (count < target) {
			  counter.innerText = count + increment;
			  setTimeout(updateCount, 20);
			} else {
			  counter.innerText = target.toLocaleString(); // formatted number
			}
		  };
		  updateCount();
		});
	  };
	
	  // Trigger only when section is in view
	  let started = false;
	  window.addEventListener("scroll", function() {
		const section = document.querySelector(".counter-section");
		const sectionTop = section.offsetTop - window.innerHeight + 100;
		if (!started && window.scrollY > sectionTop) {
		  animateCounters();
		  started = true;
		}
	  });
	
	  // Counter Js Ends 