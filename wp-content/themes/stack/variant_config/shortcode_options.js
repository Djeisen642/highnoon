// Define all section sidebar options for shortcode elements

(function(){
	
  //check for plugin data
  var product_cats = false,
  	  contact_forms = false;
  	  
  if( options_data.product_cats ){
  	  product_cats = options_data.product_cats;	
  }
  
  if( options_data.contact_forms ){
  	  contact_forms = options_data.contact_forms;	
  }
  
  mr_variant.loadShortcodeOptions([
  	{
  	   title:"Slider Options",
  	   selector: '[data-shortcode-name="stack_portfolio"][data-param-layout="carousel-1"], [data-shortcode-name="stack_portfolio"][data-param-layout="carousel-2"], [data-shortcode-name="stack_portfolio"][data-param-layout="fullscreen"], [data-shortcode-name="stack_post"][data-param-layout="carousel"], [data-shortcode-name="stack_team"][data-param-layout="carousel"], [data-shortcode-name="stack_product"][data-param-layout="carousel"]',
  	   group:[
  	      {
  	         title:"Arrow Navigation",
  	         type:"switch",
  	         attribute:"data-param-arrows",
  	         explainer:"Show the slider arrow navigation",
  	         onProperty:"true",
  	         offProperty:"false",
  	         refresh:"true",
  	         callback: "mr_variant.reloadShortcodes"
  	      },
  	      {
  	         title:"Paging Navigation",
  	         type:"switch",
  	         attribute:"data-param-paging",
  	         explainer:"Show the paging dot navigation",
  	         onProperty:"true",
  	         offProperty:"false",
  	         refresh:"true",
  	         callback: "mr_variant.reloadShortcodes"
  	      },
  	      {
  	         title:"Slideshow Speed",
  	         type:"slider",
  	         attribute:"data-param-timing",
  	         options:[
  	            "false",
  	            "1000",
  	            "2000",
  	            "3000",
  	            "4000",
  	            "5000",
  	            "6000",
  	            "7000",
  	            "8000",
  	            "9000",
  	            "10000"
  	         ],
  	         labels:[
  	            "OFF",
  	            "10 sec"
  	         ],
  	         titles:[
  	            "Off",
  	            "1 Second",
  	            "2 Seconds",
  	            "3 Seconds",
  	            "4 Seconds",
  	            "5 Seconds",
  	            "2 Seconds",
  	            "7 Seconds",
  	            "8 Seconds",
  	            "9 Seconds",
  	            "10 Seconds"
  	         ],
  	         explainer:"Adjust the duration to show each slide (in seconds)",
  	         refresh:"true",
  	         callback: "mr_variant.reloadShortcodes"
  	      }
  	   ]
  	},
    {
        title:"Team Members",
        selector:'[data-shortcode-name="stack_team"]',
        group:[
          {
             type:"text",
             title:"Quantity to show",
             attribute:"data-param-pppage",
             placeholder: "6",
             explainer:"Adjust the number of team members to show in this section.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          },
          {
             type:"dropdown",
             title:"Show which team category?",
             attribute:"data-param-filter",
             options:JSON.parse(options_data.team_cats),
             explainer:"Adjust which category of team members you want this to show.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          }
       ]
       
    },
    {
        title:"Portfolio Items",
        selector:'[data-shortcode-name="stack_portfolio"]',
        group:[
          {
             type:"text",
             title:"Quantity to show",
             attribute:"data-param-pppage",
             placeholder: "6",
             explainer:"Adjust the number of portfolio items to show in this section.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          },
          {
             type:"dropdown",
             title:"Show which portfolio category?",
             attribute:"data-param-filter",
             options:JSON.parse(options_data.portfolio_cats),
             explainer:"Adjust which category of portfolio items you want this to show.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          }
       ]
       
    },
    {
        title:"Careers Items",
        selector:'[data-shortcode-name="stack_career"]',
        group:[
          {
             type:"text",
             title:"Quantity to show",
             attribute:"data-param-pppage",
             placeholder: "6",
             explainer:"Adjust the number of Careers items to show in this section.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          },
          {
             type:"dropdown",
             title:"Show which Careers category?",
             attribute:"data-param-filter",
             options:JSON.parse(options_data.career_cats),
             explainer:"Adjust which category of Careers items you want this to show.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          }
       ]
       
    },
    {
        title:"Products",
        selector:'[data-shortcode-name="stack_product"]',
        group:[
          {
             type:"text",
             title:"Quantity to show",
             attribute:"data-param-pppage",
             placeholder: "6",
             explainer:"Adjust the number of products to show in this section.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          },
          {
             type:"dropdown",
             title:"Show which product category?",
             attribute:"data-param-filter",
             options:JSON.parse(product_cats),
             explainer:"Adjust which category of products you want this to show.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          }
       ]
       
    },
    {
        title:"Posts",
        selector:'[data-shortcode-name="stack_post"]',
        group:[
          {
             type:"text",
             title:"Quantity to show",
             attribute:"data-param-pppage",
             placeholder: "6",
             explainer:"Adjust the number of posts to show in this section.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          },
          {
             type:"dropdown",
             title:"Show which post category?",
             attribute:"data-param-filter",
             options:JSON.parse(options_data.post_cats),
             explainer:"Adjust which category of posts you want this to show.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          }
       ]
       
    },
    {
        title:"Testimonials",
        selector:'[data-shortcode-name="stack_testimonial"]',
        group:[
          {
             type:"text",
             title:"Quantity to show",
             attribute:"data-param-pppage",
             placeholder: "6",
             explainer:"Adjust the number of testimonials to show in this section.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          },
          {
             type:"dropdown",
             title:"Show which testimonial category?",
             attribute:"data-param-filter",
             options:JSON.parse(options_data.testimonial_cats),
             explainer:"Adjust which category of testimonials you want this to show.",
             refresh:true,
             callback: "mr_variant.reloadShortcodes"
          }
       ]
       
    },
    {
        title:"Contact Form 7",
        selector:'[data-shortcode-name="contact-form-7"]',
        group:[
        	{
        	   type:"text",
        	   title:"Search title",
        	   attribute:"data-param-title",
        	   placeholder: "",
        	   explainer:"Enter optional title to search if no ID selected or cannot find by ID.",
        	   refresh:true,
        	   callback: "mr_variant.reloadShortcodes"
        	},
        	{
        	   type:"dropdown",
        	   title:"Select contact form",
        	   attribute:"data-param-id",
        	   options:JSON.parse(contact_forms),
        	   explainer:"Choose previously created contact form from the drop down list.",
        	   refresh:true,
        	   callback: "mr_variant.reloadShortcodes"
        	}
       ]
       
    },
    {
       "title":"Hide contact form 7",
       "selector":'.cf7-holder',
       "type":"switch",
       "explainer":"Use this control to hide the contact form area.",
       "onClass":"hide-form",
       "offClass":"",
       "refresh":"false"
    },
    {
       "title":"Hide Alternate Shortcode",
       "selector":'.shortcode-holder-wrapper',
       "type":"switch",
       "explainer":"Use this control to hide the contact form area.",
       "onClass":"hide-form",
       "offClass":"",
       "refresh":"false"
    },
    {
       "title":"Shortcode",
       "selector":'.shortcode-holder',
       "type":"text",
       "explainer":"The shortcode you add here will be rendered in the client view of this page.",
       "attribute":"data-shortcode",
       "refresh":"true",
       "callback":"mr_variant.wp.handleShortcodeBlock"
    }

  ]);

})();