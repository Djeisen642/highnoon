<script id="vkk" type="application/json">
	{ 
		"options":[
			{	
				"type":"toggle",
				"class":"scroll-assist",
				"text":"Scroll Assist",
				"initial":"auto",
				"icon":"resize-height"
			}
		]
	}
</script>

<script id="voy" type="application/json">
	{ 
		"options":[
			{	
				"type":"toggle",
				"selector":"section:not(.page-navigator)",
				"menu":"utility",
				"submenu":"Section Controls",
				"class":"hidden-xs",
				"text":"Hide On Mobile",
				"initial":"auto",
				"icon":"phone"
			},
			{	
				"type":"toggle",
				"selector":"section:not(.imagebg):not(.bg--primary):not(.page-navigator),footer:not(.imagebg)",
				"menu":"utility",
				"submenu":"Section Controls",
				"class":"bg--secondary",
				"text":"BG Secondary",
				"initial":"auto",
				"icon":"contrast"
			},
			{	
				"type":"toggle",
				"selector":"section:not(.imagebg):not(.bg--primary):not(.page-navigator),footer:not(.imagebg)",
				"menu":"utility",
				"submenu":"Section Controls",
				"class":"bg--dark",
				"text":"BG Dark",
				"initial":"auto",
				"icon":"contrast"
			},
			{	
				"type":"toggle",
				"selector":".imagebg, .cover, section.slider",
				"menu":"utility",
				"class":"parallax",
				"text":"Parallax",
				"initial":"auto",
				"icon":"data-transfer-upload"
			},
			{	
				"type":"toggle",
				"selector":"p",
				"menu":"context",
				"class":"lead",
				"text":"Large Paragraph",
				"initial":"auto",
				"icon":"bold"
			},
			{	
				"type":"toggle",
				"selector":".btn",
				"menu":"context",
				"class":"btn--primary",
				"text":"Filled Colour",
				"initial":"auto",
				"icon":"brush"
			},
			{	
				"type":"toggle",
				"selector":"img",
				"menu":"context",
				"class":"box-shadow-wide",
				"text":"Box Shadow",
				"initial":"auto",
				"icon":"brush"
			},
			{	
				"type":"toggle",
				"selector":"img",
				"menu":"context",
				"class":"border--round",
				"text":"Rounded Border",
				"initial":"auto",
				"icon":"brush"
			},
			{	
				"type":"toggle",
				"selector":".pricing__head",
				"menu":"context",
				"class":"bg--primary",
				"text":"Featured",
				"initial":"auto",
				"icon":"brush"
			},
			{	
				"type":"toggle",
				"selector":".switchable",
				"menu":"utility",
				"class":"switchable--switch",
				"text":"Switch Sides",
				"initial":"auto",
				"icon":"resize-width"
			},
			{	
				"type":"toggle",
				"selector":"h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6",
				"menu":"context",
				"class":"type--uppercase",
				"text":"Uppercase",
				"initial":"auto",
				"icon":"text"
			},
			{	
				"type":"toggle",
				"selector":".feature-1,.feature-5",
				"menu":"context",
				"class":"feature--featured",
				"text":"Featured",
				"initial":"auto",
				"icon":"droplet"
			}
		]
	}
</script>

<script id="vnd" type="application/json">
	{ 
		"options":[
			{
				"buttonText":"Edit Placeholder",
				"modalTitle":"Edit Placeholder",
				"modalIntro":"Edit the placeholder text for this input. Placeholder text appears before the user has started typing.",
				"modalInputLabel":"Placeholder Text:",
				"selector":"textarea,input[type='text'],input[type='email'],input[type='tel'],input[type='password'],input[type='url']",
				"attribute":"placeholder",
				"modalInputIcon":"text"
			},
			{
				"buttonText":"Edit Twitter User",
				"modalTitle":"Edit Twitter User",
				"modalIntro":"Type the name of the Twitter account you'd like to show tweets from.",
				"modalInputLabel":"Account Name:",
				"selector":".tweets-feed",
				"attribute":"data-feed-name",
				"modalInputIcon":"twitter",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Instagram Client ID",
				"modalTitle":"Edit Instagram API Client ID",
				"modalIntro":"Obtain your Client ID from Instagram API. https://www.instagram.com/developer/authentication/",
				"modalInputLabel":"Client ID:",
				"selector":".instafeed",
				"attribute":"data-client-id",
				"modalInputIcon":"person",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Instagram Access Token",
				"modalTitle":"Edit Instagram API Access Token",
				"modalIntro":"Obtain your Access Token from Instagram API: https://www.instagram.com/developer/authentication/",
				"modalInputLabel":"Access Token:",
				"selector":".instafeed",
				"attribute":"data-access-token",
				"modalInputIcon":"key",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Feed",
				"modalTitle":"Edit Instagram Feed",
				"modalIntro":"Edit the Instagram feed by placing your desired username here.",
				"modalInputLabel":"Username:",
				"selector":".instafeed",
				"attribute":"data-user-name",
				"modalInputIcon":"instagram",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Form Success Message",
				"modalTitle":"Edit Form Success Message",
				"modalIntro":"Provide a message to appear when the form is submitted successfully.",
				"modalInputLabel":"Form Success Message:",
				"selector":"form[data-success]",
				"attribute":"data-success",
				"modalInputIcon":"circle-check",
				"sectionControl":"true"
			},
			{
				"buttonText":"Edit Form Error Message",
				"modalTitle":"Edit Form Error Message",
				"modalIntro":"Provide a message to appear when the form validation is rejected.",
				"modalInputLabel":"Form Error Message:",
				"selector":"form[data-error]",
				"attribute":"data-error",
				"modalInputIcon":"circle-x",
				"sectionControl":"true"
			},
			{
				"buttonText":"Edit Form Redirect Address",
				"modalTitle":"Edit Form Redirect Address",
				"modalIntro":"The \"Success\" page address to redirect user to after message is sent.   Leave blank for no redirection.",
				"modalInputLabel":"Redirect Address:",
				"selector":"form.form-email, form.form-newsletter",
				"attribute":"success-redirect",
				"modalInputIcon":"external-link",
				"sectionControl":"true"
			},
			{
				"buttonText":"Edit Google Maps API Key",
				"modalTitle":"Google Maps API Key",
				"modalIntro":"Get your Maps API key from Google and paste it here to allow styled maps in your page.",
				"modalInputLabel":"API Key:",
				"selector":"[data-maps-api-key]",
				"attribute":"data-maps-api-key",
				"modalInputIcon":"key",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Map Address",
				"modalTitle":"Map Address",
				"modalIntro":"Provide a specific address for Google Maps to center the map.",
				"modalInputLabel":"Full Address:",
				"selector":"[data-maps-api-key]",
				"attribute":"data-address",
				"modalInputIcon":"map-marker",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Map Style",
				"modalTitle":"Map Style",
				"modalIntro":"Copy JSON style code from https://snazzymaps.com/ or http://goo.gl/GJEDaO and paste below.",
				"modalInputLabel":"Maps Style JSON:",
				"selector":"[data-maps-api-key]",
				"attribute":"data-map-style",
				"modalInputIcon":"brush",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Map Zoom Level",
				"modalTitle":"Map Zoom Level",
				"modalIntro":"Provide a zoom level (number) between 1 and 18 where 18 is closer and 1 is further away.",
				"modalInputLabel":"Zoom level:",
				"selector":"[data-maps-api-key]",
				"attribute":"data-map-zoom",
				"modalInputIcon":"zoom-in",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit iframe embed",
				"modalTitle":"Embed Map",
				"modalIntro":"Paste your iframe code from Google Maps here",
				"modalInputLabel":"iframe code:",
				"selector":".map-container > iframe",
				"attribute":"no-src",
				"modalInputIcon":"map-marker",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Embedded Iframe",
				"modalTitle":"Embed Embedded Iframe",
				"modalIntro":"Paste your iframe code obtained from your video provider here",
				"modalInputLabel":"iframe code:",
				"selector":".modal-content iframe",
				"attribute":"no-src",
				"modalInputIcon":"video",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Embedded Iframe",
				"modalTitle":"Embed Embedded Iframe",
				"modalIntro":"Paste your iframe code obtained from your video provider here",
				"modalInputLabel":"iframe code:",
				"selector":".video-cover iframe",
				"attribute":"no-src",
				"modalInputIcon":"video",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Barchart",
				"modalTitle":"Edit Barchart Value",
				"modalIntro":"Enter a value from 1 - 100 to set the barchart's percentage",
				"modalInputLabel":"Value:",
				"selector":".barchart",
				"attribute":"data-value",
				"modalInputIcon":"brush",
				"sectionControl":"false",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Youtube Video",
				"modalTitle":"Edit Youtube Video",
				"modalIntro":"Enter your video's unique Youtube ID. You can retrieve this alphanumerical ID from the URL.",
				"modalInputLabel":"Video ID:",
				"selector":".youtube-background",
				"attribute":"data-video-url",
				"modalInputIcon":"video",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Adjust Overlay",
				"modalTitle":"Adjust Overlay Amount",
				"modalIntro":"Adjust this element's overlay amount as a value between 0 (no overlay) and 9 (strong overlay)",
				"modalInputLabel":"Overlay Amount (0 - 9):",
				"selector":"[data-overlay]",
				"attribute":"data-overlay",
				"modalInputIcon":"contrast",
				"sectionControl":"true",
				"refresh":"false"
			},
			{
				"buttonText":"Slider Timing",
				"modalTitle":"Adjust Slider Timing",
				"modalIntro":"A millisecond amount that each slide shows for before moving on to the next slide. The default value is 5000 (5 seconds)",
				"modalInputLabel":"Timing (ms)",
				"selector":"[data-timing]",
				"attribute":"data-timing",
				"modalInputIcon":"timer",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Slider Items",
				"modalTitle":"Amount of Slider Items",
				"modalIntro":"The default number of slide items to show on the screen in this slider.",
				"modalInputLabel":"Items",
				"selector":"[data-items]",
				"attribute":"data-items",
				"modalInputIcon":"grid-two-up",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Filter Category",
				"modalTitle":"Edit Filter Category",
				"modalIntro":"Change the category of this item",
				"modalInputLabel":"Category:",
				"selector":"[data-masonry-filter]",
				"attribute":"data-masonry-filter",
				"modalInputIcon":"tags",
				"sectionControl":"false",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Piechart Value",
				"modalTitle":"Edit Piechart Value",
				"modalIntro":"Change the percentage of the piechart",
				"modalInputLabel":"Value (0 - 100):",
				"selector":".piechart[data-value]",
				"attribute":"data-value",
				"modalInputIcon":"pie-chart",
				"sectionControl":"false",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Gradient Colors",
				"modalTitle":"Edit Gradient Colors",
				"modalIntro":"Add color hex values as a comma separated list.",
				"modalInputLabel":"Colors:",
				"selector":"[data-gradient-bg]",
				"attribute":"data-gradient-bg",
				"modalInputIcon":"droplet",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Typed Strings",
				"modalTitle":"Edit Typed Strings",
				"modalIntro":"Add strings as a comma separated list.",
				"modalInputLabel":"Strings:",
				"selector":"[data-typed-strings]",
				"attribute":"data-typed-strings",
				"modalInputIcon":"text",
				"sectionControl":"true",
				"refresh":"true"
			},
			{
				"buttonText":"Edit Radial Percentage",
				"modalTitle":"Edit Radial Percentage",
				"modalIntro":"A numeric value to determine the radial percentage.",
				"modalInputLabel":"Value (0 - 100):",
				"selector":".radial",
				"attribute":"data-value",
				"modalInputIcon":"pie-chart",
				"sectionControl":"false",
				"refresh":"true"
			}
		]
	}
</script>

<script id="variant-section-vgu" type="application/json">
	{
	   "options":[
	      {
	         "title":"Text Alignment",
	         "type":"choice",
	         "selector":"section:not(.imageblock):not([class*='cover-form-3']):not([class*='variant-gallery']):not([class*='variant-carousel-logo']):not([cass*='variant-cta-horizontal-2']):not([class*='variant-pricing-plans-4']):not([class*='variant-features-small-2']):not([class*='variant-features-small-5']):not([class*='variant-features-large-6']):not([class*='variant-process-1']):not([class*='variant-slider-']):not([class*='variant-subscribe-'])",
	         "explainer":"Change the default text alignment for this section",
	         "options":[
	            {
	               "onClass":"",
	               "html":"<span class='oi' data-glyph='align-left'></span>",
	               "title":"Align Left"
	            },
	            {
	               "onClass":"text-justify",
	               "html":"<span class='oi' data-glyph='justify-left'></span>",
	               "title":"Align Center"
	            },
	            {
	               "onClass":"text-center",
	               "html":"<span class='oi' data-glyph='align-center'></span>",
	               "title":"Align Center"
	            },
	            {
	               "onClass":"text-right",
	               "html":"<span class='oi' data-glyph='align-right'></span>",
	               "title":"Align Right"
	            }
	         ]
	      },
	      {
               "title":"Section Padding",
               "selector":"section:not([class*='height-']):not([class*='unpad'])",
               "type":"slider",
               "options":[
                  "space--xxs",
                  "space--xs",
                  "space--sm",
                  "",
                  "space--md",
                  "space--lg",
                  "space--xlg"
               ],
               "labels":[
                  "XXS",
                  "XS",
                  "SM",
                  "Auto",
                  "MD",
                  "LG",
                  "XLG"
               ],
               "explainer":"Adjust the default spacing for this section.",
               "refresh":"false"
            },
	      {
	         "title":"Parallax",
	         "selector":".imagebg:not(.slider):not(.slide):not([data-gradient-bg]):not(.modal-content)",
	         "type":"switch",
	         "explainer":"Parallax is a depth-of-field visual effect which causes the background image to scroll slower than the page.",
	         "onClass":"parallax",
	         "offClass":"",
	         "refresh":"false",
	         "callback":"mr_parallax.callback"
	      },
	      {
	         "title":"Background Effect",
	         "type":"choice",
	         "selector":".imagebg:not(.slider):not(.slide):not([data-gradient-bg]):not(.modal-content)",
	         "explainer":"Parallax is a depth-of-field visual effect which causes the background image to scroll slower than the page.  Ken Burns effect causes the background to slowly \"breathe\".",
	         "callback":"mr_parallax.callback",
	         "options":[
	            {
	               "onClass":"",
	               "html":"<span>None</span>",
	               "title":"No Effect"
	            },
	            {
	               "onClass":"parallax",
	               "html":"<span>Parallax</span>",
	               "title":"Parallax on scroll effect"
	            },
	            {
	               "onClass":"section--ken-burns",
	               "html":"<span>Ken Burns</span>",
	               "title":"Ken Burns slowly breathing effect"
	            }
	         ]
	      },
	      {
	         "title":"Invert Colours",
	         "selector":".imagebg:not(.slider):not(.slides > li):not(.feature-large-7)",
	         "type":"switch",
	         "explainer":"Invert the default light on dark text and overlay colours",
	         "onClass":"image--light",
	         "offClass":"",
	         "refresh":"false"
	      },
	      {
	         "title":"Image Overlay",
	         "selector":"section.imagebg:not(.slider):not([data-gradient-bg])",
	         "type":"slider",
	         "attribute":"data-overlay",
	         "options":[
	            "",
	            "1",
	            "2",
	            "3",
	            "4",
	            "5",
	            "6",
	            "7",
	            "8",
	            "9"
	         ],
	         "labels":[
	            "Off",
	            "Full"
	         ],
	         "explainer":"Adjust this element's overlay amount as a value between 0 (no overlay) and 9 (strong overlay)",
	         "refresh":"false"
	      },
	      {
	         "title":"Section Height",
	         "selector":"section[class*='height-']:not(.slider)",
	         "type":"slider",
	         "options":[
	            "height-auto",
	            "height-30",
	            "height-40",
	            "height-50",
	            "height-60",
	            "height-70",
	            "height-80",
	            "height-90",
	            "height-100"
	         ],
	         "labels":[
	            "Auto",
	            "",
	            "",
	            "",
	            "",
	            "",
	            "",
	            "",
	            "100%"
	         ],
	         "explainer":"Adjust this sections height from auto-height to 100% of the viewport height.",
	         "refresh":"false"
	      },
	      {
	         "title":"Background",
	         "type":"choice",
	         "selector":"section:not(.imagebg),footer:not(.imagebg)",
	         "explainer":"Select the colour for the background of this section.",
	         "options":[
	            {
	               "colourClass":"bg--site",
	               "title":"Default site background colour",
	               "onClass":""
	            },
	            {
	               "colourClass":"bg--secondary",
	               "title":"Medium background colour",
	               "onClass":"bg--secondary"
	            },
	            {
	               "colourClass":"bg--dark",
	               "title":"Dark background colour",
	               "onClass":"bg--dark"
	            },
	            {
	              "colourClass":"bg--primary",
	               "title":"Primary background colour",
	              "onClass":"bg--primary"
	             }
	         ]
	      },
	      {
	         "title":"Flip Layout",
	         "type":"choice",
	         "selector":".switchable",
	         "explainer":"Flip the layout direction from left to right.",
	         "options":[
	            {
	               "onClass":"",
	               "html":"<span>Original</span>",
	               "title":"Default site background colour"
	            },
	            {
	               "onClass":"switchable--switch",
	               "html":"<span>Flipped</span>",
	               "title":"Medium background colour"
	            }
	         ]
	      },
	      {
	         "title":"Instagram Feed",
	         "selector":".instafeed",
	         "group":[
	            {
	               "type":"text",
	               "title":"Username",
	               "attribute":"data-user-name",
	               "placeholder":"Instagram feed",
	               "explainer":"Edit the Instagram feed by placing your desired username here.",
	               "refresh":"true"
	            },
	            {
	               "type":"text",
	               "title":"Client ID",
	               "attribute":"data-client-id",
	               "placeholder":"Client ID",
	               "explainer":"Obtain your Client ID from Instagram API. https://www.instagram.com/developer/authentication/  This is required in order to set up an Instagram feed.",
	               "refresh":"true"
	            },
	            {
	               "type":"text",
	               "title":"Access Token",
	               "attribute":"data-access-token",
	               "placeholder":"Access Token",
	               "explainer":"Obtain your Access Token from Instagram API. https://www.instagram.com/developer/authentication/  This is required in order to set up an Instagram feed.",
	               "refresh":"true"
	            },
	            {
	               "title":"Number of Images",
	               "type":"slider",
	               "attribute":"data-amount",
	               "options":[
	                  "1",
	                  "2",
	                  "3",
	                  "4",
	                  "5",
	                  "6",
	                  "7",
	                  "8",
	                  "9",
	                  "10",
	                  "11",
	                  "12",
	                  "13",
	                  "14",
	                  "15",
	                  "16",
	                  "17",
	                  "18",
	                  "19",
	                  "20"
	               ],
	               "labels":[
	                  "1",
	                  "20"
	               ],
	               "explainer":"Adjust the number of images to display in the feed",
	               "refresh":"true"
	            },
	            {
	               "title":"Grid Size",
	               "type":"slider",
	               "attribute":"data-grid",
	               "options":[
	                  "1",
	                  "2",
	                  "3",
	                  "4",
	                  "5",
	                  "6",
	                  "7",
	                  "8"
	               ],
	               "labels":[
	                  "1",
	                  "2",
	                  "3",
	                  "4",
	                  "5",
	                  "6",
	                  "7",
	                  "8"
	               ],
	               "explainer":"Adjust the number of images in each row.",
	               "refresh":"false"
	            },
	            {
	               "title":"Gapless Grid",
	               "type":"switch",
	               "explainer":"Removes the gap between images.",
	               "onClass":"instafeed--gapless"
	            }
	         ]
	      },
	      {
	         "title":"Contact Form",
	         "selector":".form-email",
	         "group":[
	            {
	               "type":"text",
	               "title":"Success Message",
	               "attribute":"data-success",
	               "placeholder":"Success Message",
	               "explainer":"Provide a message to appear when the form is submitted successfully. Eg: Thanks, we will be in touch shortly.",
	               "refresh":"false"
	            },
	            {
	               "type":"text",
	               "title":"Error Message",
	               "attribute":"data-error",
	               "placeholder":"Error message",
	               "explainer":"Provide a message to appear when the form validation is rejected. Eg: Please fill all fields correctly.",
	               "refresh":"false"
	            },
	            {
	               "type":"text",
	               "title":"Success Redirect",
	               "attribute":"data-success-redirect",
	               "placeholder":"Eg:",
	               "explainer":"A URL to redirect your user's browser after the message is successfully sent. Eg: http://yourdomain.com/thanks.html .  This is not the email address to send the contact email.  Please look at mail.php in the mail folder for that setting.",
	               "refresh":"false"
	            }
	         ]
	      },
	      {
	         "title":"Google Maps",
	         "selector":".map-container[data-address]",
	         "group":[
	            {
	               "type":"text",
	               "title":"API Key",
	               "attribute":"data-maps-api-key",
	               "placeholder":"Api Key",
	               "explainer":"Get your Maps API key from Google and paste it here to allow styled maps in your page.",
	               "refresh":"true"
	            },
	            {
	               "type":"text",
	               "title":"Address",
	               "attribute":"data-address",
	               "placeholder":"Map Address",
	               "explainer":"Provide a specific address for Google Maps to center the map.  Add more addresses separated by semicolons ( ; ) to put more markers on the map.",
	               "refresh":"true"
	            },
	            {
	               "type":"text",
	               "title":"Map Style",
	               "attribute":"data-map-style",
	               "placeholder":"Map Style JSON",
	               "explainer":"Copy JSON style code from https://snazzymaps.com/ or http://goo.gl/GJEDaO and paste below.",
	               "refresh":"true"
	            },
	            {
	               "type":"slider",
	               "title":"Zoom Level",
	               "attribute":"data-map-zoom",
	               "options":[
	                  "1",
	                  "2",
	                  "3",
	                  "4",
	                  "5",
	                  "6",
	                  "7",
	                  "8",
	                  "9",
	                  "10",
	                  "11",
	                  "12",
	                  "13",
	                  "14",
	                  "15",
	                  "16",
	                  "17",
	                  "18",
	                  "19",
	                  "20"
	               ],
	               "labels":[
	                  "World",
	                  "City",
	                  "Buildings"
	               ],
	               "explainer":"Controls the zoom level of your map, where \"World\" is furthest away and \"Buildings\" is closest.",
	               "refresh":"true"
	            }
	         ]
	      },
	      {
	         "title":"WebM Video URL",
	         "selector":"video[data-src-webm]",
	         "type":"text",
	         "placeholder":"WebM Video URL",
	         "explainer":"Paste in a webm video URL",
	         "attribute":"data-src-webm",
	         "refresh":"false"
	      },
	      {
	         "title":"mp4 Video URL",
	         "selector":"video[data-src-mp4]",
	         "type":"text",
	         "placeholder":"mp4 Video URL",
	         "explainer":"Paste in a mp4 video URL",
	         "attribute":"data-src-mp4",
	         "refresh":"false"
	      },
	      {
	         "title":"Google iFrame Map",
	         "selector":"iframe[no-src*='https://www.google.com/maps/']",
	         "type":"text",
	         "placeholder":"Map embed code",
	         "explainer":"Copy the embed code from Google Maps and paste it here.",
	         "attribute":"no-src",
	         "refresh":"true"
	      },
	      {
	         "title":"Embedded iFrame Src",
	         "selector":".modal-content iframe, iframe:not([src*='maps']):not(.map-container iframe)",
	         "type":"text",
	         "placeholder":"iFrame Embed",
	         "explainer":"Paste your iframe 'src' url obtained from your video provider here",
	         "attribute":"no-src",
	         "refresh":"true"
	      },
	      {
	         "title":"Youtube Video",
	         "selector":".youtube-background",
	         "type":"text",
	         "placeholder":"Video ID",
	         "explainer":"Enter your video's unique Youtube ID. You can retrieve this alphanumerical ID from the URL.",
	         "attribute":"data-video-url",
	         "refresh":"true"
	      },
	      {
	         "title":"Video Start Time",
	         "selector":".youtube-background",
	         "type":"text",
	         "placeholder":"17",
	         "explainer":"Enter the start time (seconds) for your video to start from.",
	         "attribute":"data-start-at",
	         "refresh":"true"
	      },
	      {
	         "title":"Gradient Colours",
	         "selector":"[data-gradient-bg]",
	         "type":"text",
	         "placeholder":"Comma separated hex colours",
	         "explainer":"Add color hex values as a comma separated list. Eg: #3e8f2c,#3f6eee,#95d236",
	         "attribute":"data-gradient-bg",
	         "refresh":"true"
	      },
	      {
	         "title":"Typed Text",
	         "selector":"[data-typed-strings]",
	         "type":"text",
	         "placeholder":"Comma separated hex colours",
	         "explainer":"Add strings as a comma separated list. Eg: Entrepreneurs,Thinkers,Makers,Doers",
	         "attribute":"data-typed-strings",
	         "refresh":"true"
	      },
	      {
	         "title":"Slider",
	         "selector":".slider:not(.variant-shortcode .slider)",
	         "group":[
	            {
	               "title":"Arrow Navigation",
	               "type":"switch",
	               "attribute":"data-arrows",
	               "explainer":"Show the slider arrow navigation",
	               "onProperty":"true",
	               "offProperty":"",
	               "refresh":"true"
	            },
	            {
	               "title":"Paging Navigation",
	               "type":"switch",
	               "attribute":"data-paging",
	               "explainer":"Show the paging dot navigation",
	               "onProperty":"true",
	               "offProperty":"",
	               "refresh":"true"
	            },
	            {
	               "title":"Slideshow Speed",
	               "type":"slider",
	               "attribute":"data-timing",
	               "options":[
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
	               "labels":[
	                  "OFF",
	                  "10 sec"
	               ],
	               "titles":[
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
	               "explainer":"Adjust the duration to show each slide (in seconds)",
	               "refresh":"true"
	            }
	         ]
	      },
	      {
	         "title":"Countdown",
	         "selector":".countdown",
	         "group":[
	            {
	               "title":"Countdown Date",
	               "type":"text",
	               "placeholder":"MM/DD/YYYY",
	               "explainer":"The date to count down to in MM/DD/YYYY format",
	               "attribute":"data-date",
	               "refresh":"true"
	            },
	            {
	               "title":"Fallback Text",
	               "type":"text",
	               "placeholder":"Fallback text",
	               "explainer":"Text to show once countdown has expired",
	               "attribute":"data-date-fallback",
	               "refresh":"true"
	            },
	            {
	               "title":"Days Text",
	               "type":"text",
	               "placeholder":"Text to show for 'days'",
	               "explainer":"The text to show instead of 'days'",
	               "attribute":"data-days-text",
	               "refresh":"true"
	            }
	         ]
	      },
	      {
	         "title":"Filter 'All' Text",
	         "selector":".masonry__filters",
	         "type":"text",
	         "placeholder":"Filter 'all' text",
	         "explainer":"Text to show for default 'all' filters",
	         "attribute":"data-filter-all-text",
	         "refresh":"true"
	      },
	      {
	         "title":"Twitter",
	         "selector":".tweets-feed",
	         "group":[
	            {
	               "title":"Twitter Account Name",
	               "type":"text",
	               "placeholder":"Twitter feed",
	               "explainer":"Enter the username/ twitter handle of the feed you want to show.",
	               "attribute":"data-feed-name",
	               "refresh":"true"
	            },
	            {
	               "title":"Amount Of Tweets",
	               "type":"slider",
	               "attribute":"data-amount",
	               "options":[
	                  "1",
	                  "2",
	                  "3",
	                  "4",
	                  "5",
	                  "5",
	                  "6",
	                  "7",
	                  "8",
	                  "9",
	                  "10"
	               ],
	               "labels":[
	                  "1",
	                  "10"
	               ],
	               "explainer":"Amount of Tweets to fetch from your account",
	               "refresh":"true"
	            }
	         ]
	      },
	      {
	         "title":"Accordion Panels",
	         "type":"choice",
	         "selector":".accordion",
	         "explainer":"Switch the default interaction mode for the accordion",
	         "options":[
	            {
	               "onClass":"",
	               "html":"<span>Multiple Open</span>",
	               "title":"Allow multiple panels to be open"
	            },
	            {
	               "onClass":"accordion--oneopen",
	               "html":"<span>One Open</span>",
	               "title":"Allow only one panel to be open"
	            }
	         ],
	         "refresh":"true"
	      },
	      {
	         "title":"Subscribe Form",
	         "type":"button",
	         "buttonText":"Embed form code",
	         "selector":"form[action*=\"createsend.com\"], form[action*=\"list-manage.com\"]",
	         "explainer":"Embed your form code from Campaign Monitor or MailChimp.",
	         "callback":"promptEditForm"
	      }
	   ]
	}
</script>

<script id="voi" type="application/json">
	{
		"iconPacks":[
			{
				"name":"Icons Mind",
				"url":"https://goo.gl/xoS6i9",
				"headString":"",
				"type":"class",
	      "iconPrefix":"icon-",
				"iconClass":"",
				"icons":["icon-A-Z","icon-Aa","icon-Add-Bag","icon-Add-Basket","icon-Add-Cart","icon-Add-File","icon-Add-SpaceAfterParagraph","icon-Add-SpaceBeforeParagraph","icon-Add-User","icon-Add-UserStar","icon-Add-Window","icon-Add","icon-Address-Book","icon-Address-Book2","icon-Administrator","icon-Aerobics-2","icon-Aerobics-3","icon-Aerobics","icon-Affiliate","icon-Aim","icon-Air-Balloon","icon-Airbrush","icon-Airship","icon-Alarm-Clock","icon-Alarm-Clock2","icon-Alarm","icon-Alien-2","icon-Alien","icon-Aligator","icon-Align-Center","icon-Align-JustifyAll","icon-Align-JustifyCenter","icon-Align-JustifyLeft","icon-Align-JustifyRight","icon-Align-Left","icon-Align-Right","icon-Alpha","icon-Ambulance","icon-AMX","icon-Anchor-2","icon-Anchor","icon-Android-Store","icon-Android","icon-Angel-Smiley","icon-Angel","icon-Angry","icon-Apple-Bite","icon-Apple-Store","icon-Apple","icon-Approved-Window","icon-Aquarius-2","icon-Aquarius","icon-Archery-2","icon-Archery","icon-Argentina","icon-Aries-2","icon-Aries","icon-Army-Key","icon-Arrow-Around","icon-Arrow-Back3","icon-Arrow-Back","icon-Arrow-Back2","icon-Arrow-Barrier","icon-Arrow-Circle","icon-Arrow-Cross","icon-Arrow-Down","icon-Arrow-Down2","icon-Arrow-Down3","icon-Arrow-DowninCircle","icon-Arrow-Fork","icon-Arrow-Forward","icon-Arrow-Forward2","icon-Arrow-From","icon-Arrow-Inside","icon-Arrow-Inside45","icon-Arrow-InsideGap","icon-Arrow-InsideGap45","icon-Arrow-Into","icon-Arrow-Join","icon-Arrow-Junction","icon-Arrow-Left","icon-Arrow-Left2","icon-Arrow-LeftinCircle","icon-Arrow-Loop","icon-Arrow-Merge","icon-Arrow-Mix","icon-Arrow-Next","icon-Arrow-OutLeft","icon-Arrow-OutRight","icon-Arrow-Outside","icon-Arrow-Outside45","icon-Arrow-OutsideGap","icon-Arrow-OutsideGap45","icon-Arrow-Over","icon-Arrow-Refresh","icon-Arrow-Refresh2","icon-Arrow-Right","icon-Arrow-Right2","icon-Arrow-RightinCircle","icon-Arrow-Shuffle","icon-Arrow-Squiggly","icon-Arrow-Through","icon-Arrow-To","icon-Arrow-TurnLeft","icon-Arrow-TurnRight","icon-Arrow-Up","icon-Arrow-Up2","icon-Arrow-Up3","icon-Arrow-UpinCircle","icon-Arrow-XLeft","icon-Arrow-XRight","icon-Ask","icon-Assistant","icon-Astronaut","icon-At-Sign","icon-ATM","icon-Atom","icon-Audio","icon-Auto-Flash","icon-Autumn","icon-Baby-Clothes","icon-Baby-Clothes2","icon-Baby-Cry","icon-Baby","icon-Back2","icon-Back-Media","icon-Back-Music","icon-Back","icon-Background","icon-Bacteria","icon-Bag-Coins","icon-Bag-Items","icon-Bag-Quantity","icon-Bag","icon-Bakelite","icon-Ballet-Shoes","icon-Balloon","icon-Banana","icon-Band-Aid","icon-Bank","icon-Bar-Chart","icon-Bar-Chart2","icon-Bar-Chart3","icon-Bar-Chart4","icon-Bar-Chart5","icon-Bar-Code","icon-Barricade-2","icon-Barricade","icon-Baseball","icon-Basket-Ball","icon-Basket-Coins","icon-Basket-Items","icon-Basket-Quantity","icon-Bat-2","icon-Bat","icon-Bathrobe","icon-Batman-Mask","icon-Battery-0","icon-Battery-25","icon-Battery-50","icon-Battery-75","icon-Battery-100","icon-Battery-Charge","icon-Bear","icon-Beard-2","icon-Beard-3","icon-Beard","icon-Bebo","icon-Bee","icon-Beer-Glass","icon-Beer","icon-Bell-2","icon-Bell","icon-Belt-2","icon-Belt-3","icon-Belt","icon-Berlin-Tower","icon-Beta","icon-Betvibes","icon-Bicycle-2","icon-Bicycle-3","icon-Bicycle","icon-Big-Bang","icon-Big-Data","icon-Bike-Helmet","icon-Bikini","icon-Bilk-Bottle2","icon-Billing","icon-Bing","icon-Binocular","icon-Bio-Hazard","icon-Biotech","icon-Bird-DeliveringLetter","icon-Bird","icon-Birthday-Cake","icon-Bisexual","icon-Bishop","icon-Bitcoin","icon-Black-Cat","icon-Blackboard","icon-Blinklist","icon-Block-Cloud","icon-Block-Window","icon-Blogger","icon-Blood","icon-Blouse","icon-Blueprint","icon-Board","icon-Bodybuilding","icon-Bold-Text","icon-Bone","icon-Bones","icon-Book","icon-Bookmark","icon-Books-2","icon-Books","icon-Boom","icon-Boot-2","icon-Boot","icon-Bottom-ToTop","icon-Bow-2","icon-Bow-3","icon-Bow-4","icon-Bow-5","icon-Bow-6","icon-Bow","icon-Bowling-2","icon-Bowling","icon-Box2","icon-Box-Close","icon-Box-Full","icon-Box-Open","icon-Box-withFolders","icon-Box","icon-Boy","icon-Bra","icon-Brain-2","icon-Brain-3","icon-Brain","icon-Brazil","icon-Bread-2","icon-Bread","icon-Bridge","icon-Brightkite","icon-Broke-Link2","icon-Broken-Link","icon-Broom","icon-Brush","icon-Bucket","icon-Bug","icon-Building","icon-Bulleted-List","icon-Bus-2","icon-Bus","icon-Business-Man","icon-Business-ManWoman","icon-Business-Mens","icon-Business-Woman","icon-Butterfly","icon-Button","icon-Cable-Car","icon-Cake","icon-Calculator-2","icon-Calculator-3","icon-Calculator","icon-Calendar-2","icon-Calendar-3","icon-Calendar-4","icon-Calendar-Clock","icon-Calendar","icon-Camel","icon-Camera-2","icon-Camera-3","icon-Camera-4","icon-Camera-5","icon-Camera-Back","icon-Camera","icon-Can-2","icon-Can","icon-Canada","icon-Cancer-2","icon-Cancer-3","icon-Cancer","icon-Candle","icon-Candy-Cane","icon-Candy","icon-Cannon","icon-Cap-2","icon-Cap-3","icon-Cap-Smiley","icon-Cap","icon-Capricorn-2","icon-Capricorn","icon-Car-2","icon-Car-3","icon-Car-Coins","icon-Car-Items","icon-Car-Wheel","icon-Car","icon-Cardigan","icon-Cardiovascular","icon-Cart-Quantity","icon-Casette-Tape","icon-Cash-Register","icon-Cash-register2","icon-Castle","icon-Cat","icon-Cathedral","icon-Cauldron","icon-CD-2","icon-CD-Cover","icon-CD","icon-Cello","icon-Celsius","icon-Chacked-Flag","icon-Chair","icon-Charger","icon-Check-2","icon-Check","icon-Checked-User","icon-Checkmate","icon-Checkout-Bag","icon-Checkout-Basket","icon-Checkout","icon-Cheese","icon-Cheetah","icon-Chef-Hat","icon-Chef-Hat2","icon-Chef","icon-Chemical-2","icon-Chemical-3","icon-Chemical-4","icon-Chemical-5","icon-Chemical","icon-Chess-Board","icon-Chess","icon-Chicken","icon-Chile","icon-Chimney","icon-China","icon-Chinese-Temple","icon-Chip","icon-Chopsticks-2","icon-Chopsticks","icon-Christmas-Ball","icon-Christmas-Bell","icon-Christmas-Candle","icon-Christmas-Hat","icon-Christmas-Sleigh","icon-Christmas-Snowman","icon-Christmas-Sock","icon-Christmas-Tree","icon-Christmas","icon-Chrome","icon-Chrysler-Building","icon-Cinema","icon-Circular-Point","icon-City-Hall","icon-Clamp","icon-Clapperboard-Close","icon-Clapperboard-Open","icon-Claps","icon-Clef","icon-Clinic","icon-Clock-2","icon-Clock-3","icon-Clock-4","icon-Clock-Back","icon-Clock-Forward","icon-Clock","icon-Close-Window","icon-Close","icon-Clothing-Store","icon-Cloud--","icon-Cloud-","icon-Cloud-Camera","icon-Cloud-Computer","icon-Cloud-Email","icon-Cloud-Hail","icon-Cloud-Laptop","icon-Cloud-Lock","icon-Cloud-Moon","icon-Cloud-Music","icon-Cloud-Picture","icon-Cloud-Rain","icon-Cloud-Remove","icon-Cloud-Secure","icon-Cloud-Settings","icon-Cloud-Smartphone","icon-Cloud-Snow","icon-Cloud-Sun","icon-Cloud-Tablet","icon-Cloud-Video","icon-Cloud-Weather","icon-Cloud","icon-Clouds-Weather","icon-Clouds","icon-Clown","icon-CMYK","icon-Coat","icon-Cocktail","icon-Coconut","icon-Code-Window","icon-Coding","icon-Coffee-2","icon-Coffee-Bean","icon-Coffee-Machine","icon-Coffee-toGo","icon-Coffee","icon-Coffin","icon-Coin","icon-Coins-2","icon-Coins-3","icon-Coins","icon-Colombia","icon-Colosseum","icon-Column-2","icon-Column-3","icon-Column","icon-Comb-2","icon-Comb","icon-Communication-Tower","icon-Communication-Tower2","icon-Compass-2","icon-Compass-3","icon-Compass-4","icon-Compass-Rose","icon-Compass","icon-Computer-2","icon-Computer-3","icon-Computer-Secure","icon-Computer","icon-Conference","icon-Confused","icon-Conservation","icon-Consulting","icon-Contrast","icon-Control-2","icon-Control","icon-Cookie-Man","icon-Cookies","icon-Cool-Guy","icon-Cool","icon-Copyright","icon-Costume","icon-Couple-Sign","icon-Cow","icon-CPU","icon-Crane","icon-Cranium","icon-Credit-Card","icon-Credit-Card2","icon-Credit-Card3","icon-Cricket","icon-Criminal","icon-Croissant","icon-Crop-2","icon-Crop-3","icon-Crown-2","icon-Crown","icon-Crying","icon-Cube-Molecule","icon-Cube-Molecule2","icon-Cupcake","icon-Cursor-Click","icon-Cursor-Click2","icon-Cursor-Move","icon-Cursor-Move2","icon-Cursor-Select","icon-Cursor","icon-D-Eyeglasses","icon-D-Eyeglasses2","icon-Dam","icon-Danemark","icon-Danger-2","icon-Danger","icon-Dashboard","icon-Data-Backup","icon-Data-Block","icon-Data-Center","icon-Data-Clock","icon-Data-Cloud","icon-Data-Compress","icon-Data-Copy","icon-Data-Download","icon-Data-Financial","icon-Data-Key","icon-Data-Lock","icon-Data-Network","icon-Data-Password","icon-Data-Power","icon-Data-Refresh","icon-Data-Save","icon-Data-Search","icon-Data-Security","icon-Data-Settings","icon-Data-Sharing","icon-Data-Shield","icon-Data-Signal","icon-Data-Storage","icon-Data-Stream","icon-Data-Transfer","icon-Data-Unlock","icon-Data-Upload","icon-Data-Yes","icon-Data","icon-David-Star","icon-Daylight","icon-Death","icon-Debian","icon-Dec","icon-Decrase-Inedit","icon-Deer-2","icon-Deer","icon-Delete-File","icon-Delete-Window","icon-Delicious","icon-Depression","icon-Deviantart","icon-Device-SyncwithCloud","icon-Diamond","icon-Dice-2","icon-Dice","icon-Digg","icon-Digital-Drawing","icon-Diigo","icon-Dinosaur","icon-Diploma-2","icon-Diploma","icon-Direction-East","icon-Direction-North","icon-Direction-South","icon-Direction-West","icon-Director","icon-Disk","icon-Dj","icon-DNA-2","icon-DNA-Helix","icon-DNA","icon-Doctor","icon-Dog","icon-Dollar-Sign","icon-Dollar-Sign2","icon-Dollar","icon-Dolphin","icon-Domino","icon-Door-Hanger","icon-Door","icon-Doplr","icon-Double-Circle","icon-Double-Tap","icon-Doughnut","icon-Dove","icon-Down-2","icon-Down-3","icon-Down-4","icon-Down","icon-Download-2","icon-Download-fromCloud","icon-Download-Window","icon-Download","icon-Downward","icon-Drag-Down","icon-Drag-Left","icon-Drag-Right","icon-Drag-Up","icon-Drag","icon-Dress","icon-Drill-2","icon-Drill","icon-Drop","icon-Dropbox","icon-Drum","icon-Dry","icon-Duck","icon-Dumbbell","icon-Duplicate-Layer","icon-Duplicate-Window","icon-DVD","icon-Eagle","icon-Ear","icon-Earphones-2","icon-Earphones","icon-Eciicon","icon-Edit-Map","icon-Edit","icon-Eggs","icon-Egypt","icon-Eifel-Tower","icon-eject-2","icon-Eject","icon-El-Castillo","icon-Elbow","icon-Electric-Guitar","icon-Electricity","icon-Elephant","icon-Email","icon-Embassy","icon-Empire-StateBuilding","icon-Empty-Box","icon-End2","icon-End-2","icon-End","icon-Endways","icon-Engineering","icon-Envelope-2","icon-Envelope","icon-Environmental-2","icon-Environmental-3","icon-Environmental","icon-Equalizer","icon-Eraser-2","icon-Eraser-3","icon-Eraser","icon-Error-404Window","icon-Euro-Sign","icon-Euro-Sign2","icon-Euro","icon-Evernote","icon-Evil","icon-Explode","icon-Eye-2","icon-Eye-Blind","icon-Eye-Invisible","icon-Eye-Scan","icon-Eye-Visible","icon-Eye","icon-Eyebrow-2","icon-Eyebrow-3","icon-Eyebrow","icon-Eyeglasses-Smiley","icon-Eyeglasses-Smiley2","icon-Face-Style","icon-Face-Style2","icon-Face-Style3","icon-Face-Style4","icon-Face-Style5","icon-Face-Style6","icon-Facebook-2","icon-Facebook","icon-Factory-2","icon-Factory","icon-Fahrenheit","icon-Family-Sign","icon-Fan","icon-Farmer","icon-Fashion","icon-Favorite-Window","icon-Fax","icon-Feather","icon-Feedburner","icon-Female-2","icon-Female-Sign","icon-Female","icon-File-Block","icon-File-Bookmark","icon-File-Chart","icon-File-Clipboard","icon-File-ClipboardFileText","icon-File-ClipboardTextImage","icon-File-Cloud","icon-File-Copy","icon-File-Copy2","icon-File-CSV","icon-File-Download","icon-File-Edit","icon-File-Excel","icon-File-Favorite","icon-File-Fire","icon-File-Graph","icon-File-Hide","icon-File-Horizontal","icon-File-HorizontalText","icon-File-HTML","icon-File-JPG","icon-File-Link","icon-File-Loading","icon-File-Lock","icon-File-Love","icon-File-Music","icon-File-Network","icon-File-Pictures","icon-File-Pie","icon-File-Presentation","icon-File-Refresh","icon-File-Search","icon-File-Settings","icon-File-Share","icon-File-TextImage","icon-File-Trash","icon-File-TXT","icon-File-Upload","icon-File-Video","icon-File-Word","icon-File-Zip","icon-File","icon-Files","icon-Film-Board","icon-Film-Cartridge","icon-Film-Strip","icon-Film-Video","icon-Film","icon-Filter-2","icon-Filter","icon-Financial","icon-Find-User","icon-Finger-DragFourSides","icon-Finger-DragTwoSides","icon-Finger-Print","icon-Finger","icon-Fingerprint-2","icon-Fingerprint","icon-Fire-Flame","icon-Fire-Flame2","icon-Fire-Hydrant","icon-Fire-Staion","icon-Firefox","icon-Firewall","icon-First-Aid","icon-First","icon-Fish-Food","icon-Fish","icon-Fit-To","icon-Fit-To2","icon-Five-Fingers","icon-Five-FingersDrag","icon-Five-FingersDrag2","icon-Five-FingersTouch","icon-Flag-2","icon-Flag-3","icon-Flag-4","icon-Flag-5","icon-Flag-6","icon-Flag","icon-Flamingo","icon-Flash-2","icon-Flash-Video","icon-Flash","icon-Flashlight","icon-Flask-2","icon-Flask","icon-Flick","icon-Flickr","icon-Flowerpot","icon-Fluorescent","icon-Fog-Day","icon-Fog-Night","icon-Folder-Add","icon-Folder-Archive","icon-Folder-Binder","icon-Folder-Binder2","icon-Folder-Block","icon-Folder-Bookmark","icon-Folder-Close","icon-Folder-Cloud","icon-Folder-Delete","icon-Folder-Download","icon-Folder-Edit","icon-Folder-Favorite","icon-Folder-Fire","icon-Folder-Hide","icon-Folder-Link","icon-Folder-Loading","icon-Folder-Lock","icon-Folder-Love","icon-Folder-Music","icon-Folder-Network","icon-Folder-Open","icon-Folder-Open2","icon-Folder-Organizing","icon-Folder-Pictures","icon-Folder-Refresh","icon-Folder-Remove-","icon-Folder-Search","icon-Folder-Settings","icon-Folder-Share","icon-Folder-Trash","icon-Folder-Upload","icon-Folder-Video","icon-Folder-WithDocument","icon-Folder-Zip","icon-Folder","icon-Folders","icon-Font-Color","icon-Font-Name","icon-Font-Size","icon-Font-Style","icon-Font-StyleSubscript","icon-Font-StyleSuperscript","icon-Font-Window","icon-Foot-2","icon-Foot","icon-Football-2","icon-Football","icon-Footprint-2","icon-Footprint-3","icon-Footprint","icon-Forest","icon-Fork","icon-Formspring","icon-Formula","icon-Forsquare","icon-Forward","icon-Fountain-Pen","icon-Four-Fingers","icon-Four-FingersDrag","icon-Four-FingersDrag2","icon-Four-FingersTouch","icon-Fox","icon-Frankenstein","icon-French-Fries","icon-Friendfeed","icon-Friendster","icon-Frog","icon-Fruits","icon-Fuel","icon-Full-Bag","icon-Full-Basket","icon-Full-Cart","icon-Full-Moon","icon-Full-Screen","icon-Full-Screen2","icon-Full-View","icon-Full-View2","icon-Full-ViewWindow","icon-Function","icon-Funky","icon-Funny-Bicycle","icon-Furl","icon-Gamepad-2","icon-Gamepad","icon-Gas-Pump","icon-Gaugage-2","icon-Gaugage","icon-Gay","icon-Gear-2","icon-Gear","icon-Gears-2","icon-Gears","icon-Geek-2","icon-Geek","icon-Gemini-2","icon-Gemini","icon-Genius","icon-Gentleman","icon-Geo--","icon-Geo-","icon-Geo-Close","icon-Geo-Love","icon-Geo-Number","icon-Geo-Star","icon-Geo","icon-Geo2--","icon-Geo2-","icon-Geo2-Close","icon-Geo2-Love","icon-Geo2-Number","icon-Geo2-Star","icon-Geo2","icon-Geo3--","icon-Geo3-","icon-Geo3-Close","icon-Geo3-Love","icon-Geo3-Number","icon-Geo3-Star","icon-Geo3","icon-Gey","icon-Gift-Box","icon-Giraffe","icon-Girl","icon-Glass-Water","icon-Glasses-2","icon-Glasses-3","icon-Glasses","icon-Global-Position","icon-Globe-2","icon-Globe","icon-Gloves","icon-Go-Bottom","icon-Go-Top","icon-Goggles","icon-Golf-2","icon-Golf","icon-Google-Buzz","icon-Google-Drive","icon-Google-Play","icon-Google-Plus","icon-Google","icon-Gopro","icon-Gorilla","icon-Gowalla","icon-Grave","icon-Graveyard","icon-Greece","icon-Green-Energy","icon-Green-House","icon-Guitar","icon-Gun-2","icon-Gun-3","icon-Gun","icon-Gymnastics","icon-Hair-2","icon-Hair-3","icon-Hair-4","icon-Hair","icon-Half-Moon","icon-Halloween-HalfMoon","icon-Halloween-Moon","icon-Hamburger","icon-Hammer","icon-Hand-Touch","icon-Hand-Touch2","icon-Hand-TouchSmartphone","icon-Hand","icon-Hands","icon-Handshake","icon-Hanger","icon-Happy","icon-Hat-2","icon-Hat","icon-Haunted-House","icon-HD-Video","icon-HD","icon-HDD","icon-Headphone","icon-Headphones","icon-Headset","icon-Heart-2","icon-Heart","icon-Heels-2","icon-Heels","icon-Height-Window","icon-Helicopter-2","icon-Helicopter","icon-Helix-2","icon-Hello","icon-Helmet-2","icon-Helmet-3","icon-Helmet","icon-Hipo","icon-Hipster-Glasses","icon-Hipster-Glasses2","icon-Hipster-Glasses3","icon-Hipster-Headphones","icon-Hipster-Men","icon-Hipster-Men2","icon-Hipster-Men3","icon-Hipster-Sunglasses","icon-Hipster-Sunglasses2","icon-Hipster-Sunglasses3","icon-Hokey","icon-Holly","icon-Home-2","icon-Home-3","icon-Home-4","icon-Home-5","icon-Home-Window","icon-Home","icon-Homosexual","icon-Honey","icon-Hong-Kong","icon-Hoodie","icon-Horror","icon-Horse","icon-Hospital-2","icon-Hospital","icon-Host","icon-Hot-Dog","icon-Hotel","icon-Hour","icon-Hub","icon-Humor","icon-Hurt","icon-Ice-Cream","icon-ICQ","icon-ID-2","icon-ID-3","icon-ID-Card","icon-Idea-2","icon-Idea-3","icon-Idea-4","icon-Idea-5","icon-Idea","icon-Identification-Badge","icon-ImDB","icon-Inbox-Empty","icon-Inbox-Forward","icon-Inbox-Full","icon-Inbox-Into","icon-Inbox-Out","icon-Inbox-Reply","icon-Inbox","icon-Increase-Inedit","icon-Indent-FirstLine","icon-Indent-LeftMargin","icon-Indent-RightMargin","icon-India","icon-Info-Window","icon-Information","icon-Inifity","icon-Instagram","icon-Internet-2","icon-Internet-Explorer","icon-Internet-Smiley","icon-Internet","icon-iOS-Apple","icon-Israel","icon-Italic-Text","icon-Jacket-2","icon-Jacket","icon-Jamaica","icon-Japan","icon-Japanese-Gate","icon-Jeans","icon-Jeep-2","icon-Jeep","icon-Jet","icon-Joystick","icon-Juice","icon-Jump-Rope","icon-Kangoroo","icon-Kenya","icon-Key-2","icon-Key-3","icon-Key-Lock","icon-Key","icon-Keyboard","icon-Keyboard3","icon-Keypad","icon-King-2","icon-King","icon-Kiss","icon-Knee","icon-Knife-2","icon-Knife","icon-Knight","icon-Koala","icon-Korea","icon-Lamp","icon-Landscape-2","icon-Landscape","icon-Lantern","icon-Laptop-2","icon-Laptop-3","icon-Laptop-Phone","icon-Laptop-Secure","icon-Laptop-Tablet","icon-Laptop","icon-Laser","icon-Last-FM","icon-Last","icon-Laughing","icon-Layer-1635","icon-Layer-1646","icon-Layer-Backward","icon-Layer-Forward","icon-Leafs-2","icon-Leafs","icon-Leaning-Tower","icon-Left--Right","icon-Left--Right3","icon-Left-2","icon-Left-3","icon-Left-4","icon-Left-ToRight","icon-Left","icon-Leg-2","icon-Leg","icon-Lego","icon-Lemon","icon-Len-2","icon-Len-3","icon-Len","icon-Leo-2","icon-Leo","icon-Leopard","icon-Lesbian","icon-Lesbians","icon-Letter-Close","icon-Letter-Open","icon-Letter-Sent","icon-Libra-2","icon-Libra","icon-Library-2","icon-Library","icon-Life-Jacket","icon-Life-Safer","icon-Light-Bulb","icon-Light-Bulb2","icon-Light-BulbLeaf","icon-Lighthouse","icon-Like-2","icon-Like","icon-Line-Chart","icon-Line-Chart2","icon-Line-Chart3","icon-Line-Chart4","icon-Line-Spacing","icon-Line-SpacingText","icon-Link-2","icon-Link","icon-Linkedin-2","icon-Linkedin","icon-Linux","icon-Lion","icon-Livejournal","icon-Loading-2","icon-Loading-3","icon-Loading-Window","icon-Loading","icon-Location-2","icon-Location","icon-Lock-2","icon-Lock-3","icon-Lock-User","icon-Lock-Window","icon-Lock","icon-Lollipop-2","icon-Lollipop-3","icon-Lollipop","icon-Loop","icon-Loud","icon-Loudspeaker","icon-Love-2","icon-Love-User","icon-Love-Window","icon-Love","icon-Lowercase-Text","icon-Luggafe-Front","icon-Luggage-2","icon-Macro","icon-Magic-Wand","icon-Magnet","icon-Magnifi-Glass-","icon-Magnifi-Glass","icon-Magnifi-Glass2","icon-Mail-2","icon-Mail-3","icon-Mail-Add","icon-Mail-Attachement","icon-Mail-Block","icon-Mail-Delete","icon-Mail-Favorite","icon-Mail-Forward","icon-Mail-Gallery","icon-Mail-Inbox","icon-Mail-Link","icon-Mail-Lock","icon-Mail-Love","icon-Mail-Money","icon-Mail-Open","icon-Mail-Outbox","icon-Mail-Password","icon-Mail-Photo","icon-Mail-Read","icon-Mail-Removex","icon-Mail-Reply","icon-Mail-ReplyAll","icon-Mail-Search","icon-Mail-Send","icon-Mail-Settings","icon-Mail-Unread","icon-Mail-Video","icon-Mail-withAtSign","icon-Mail-WithCursors","icon-Mail","icon-Mailbox-Empty","icon-Mailbox-Full","icon-Male-2","icon-Male-Sign","icon-Male","icon-MaleFemale","icon-Man-Sign","icon-Management","icon-Mans-Underwear","icon-Mans-Underwear2","icon-Map-Marker","icon-Map-Marker2","icon-Map-Marker3","icon-Map","icon-Map2","icon-Marker-2","icon-Marker-3","icon-Marker","icon-Martini-Glass","icon-Mask","icon-Master-Card","icon-Maximize-Window","icon-Maximize","icon-Medal-2","icon-Medal-3","icon-Medal","icon-Medical-Sign","icon-Medicine-2","icon-Medicine-3","icon-Medicine","icon-Megaphone","icon-Memory-Card","icon-Memory-Card2","icon-Memory-Card3","icon-Men","icon-Menorah","icon-Mens","icon-Metacafe","icon-Mexico","icon-Mic","icon-Microphone-2","icon-Microphone-3","icon-Microphone-4","icon-Microphone-5","icon-Microphone-6","icon-Microphone-7","icon-Microphone","icon-Microscope","icon-Milk-Bottle","icon-Mine","icon-Minimize-Maximize-Close-Window","icon-Minimize-Window","icon-Minimize","icon-Mirror","icon-Mixer","icon-Mixx","icon-Money-2","icon-Money-Bag","icon-Money-Smiley","icon-Money","icon-Monitor-2","icon-Monitor-3","icon-Monitor-4","icon-Monitor-5","icon-Monitor-Analytics","icon-Monitor-Laptop","icon-Monitor-phone","icon-Monitor-Tablet","icon-Monitor-Vertical","icon-Monitor","icon-Monitoring","icon-Monkey","icon-Monster","icon-Morocco","icon-Motorcycle","icon-Mouse-2","icon-Mouse-3","icon-Mouse-4","icon-Mouse-Pointer","icon-Mouse","icon-Moustache-Smiley","icon-Movie-Ticket","icon-Movie","icon-Mp3-File","icon-Museum","icon-Mushroom","icon-Music-Note","icon-Music-Note2","icon-Music-Note3","icon-Music-Note4","icon-Music-Player","icon-Mustache-2","icon-Mustache-3","icon-Mustache-4","icon-Mustache-5","icon-Mustache-6","icon-Mustache-7","icon-Mustache-8","icon-Mustache","icon-Mute","icon-Myspace","icon-Navigat-Start","icon-Navigate-End","icon-Navigation-LeftWindow","icon-Navigation-RightWindow","icon-Nepal","icon-Netscape","icon-Network-Window","icon-Network","icon-Neutron","icon-New-Mail","icon-New-Tab","icon-Newspaper-2","icon-Newspaper","icon-Newsvine","icon-Next2","icon-Next-3","icon-Next-Music","icon-Next","icon-No-Battery","icon-No-Drop","icon-No-Flash","icon-No-Smoking","icon-Noose","icon-Normal-Text","icon-Note","icon-Notepad-2","icon-Notepad","icon-Nuclear","icon-Numbering-List","icon-Nurse","icon-Office-Lamp","icon-Office","icon-Oil","icon-Old-Camera","icon-Old-Cassette","icon-Old-Clock","icon-Old-Radio","icon-Old-Sticky","icon-Old-Sticky2","icon-Old-Telephone","icon-Old-TV","icon-On-Air","icon-On-Off-2","icon-On-Off-3","icon-On-off","icon-One-Finger","icon-One-FingerTouch","icon-One-Window","icon-Open-Banana","icon-Open-Book","icon-Opera-House","icon-Opera","icon-Optimization","icon-Orientation-2","icon-Orientation-3","icon-Orientation","icon-Orkut","icon-Ornament","icon-Over-Time","icon-Over-Time2","icon-Owl","icon-Pac-Man","icon-Paint-Brush","icon-Paint-Bucket","icon-Paintbrush","icon-Palette","icon-Palm-Tree","icon-Panda","icon-Panorama","icon-Pantheon","icon-Pantone","icon-Pants","icon-Paper-Plane","icon-Paper","icon-Parasailing","icon-Parrot","icon-Password-2shopping","icon-Password-Field","icon-Password-shopping","icon-Password","icon-pause-2","icon-Pause","icon-Paw","icon-Pawn","icon-Paypal","icon-Pen-2","icon-Pen-3","icon-Pen-4","icon-Pen-5","icon-Pen-6","icon-Pen","icon-Pencil-Ruler","icon-Pencil","icon-Penguin","icon-Pentagon","icon-People-onCloud","icon-Pepper-withFire","icon-Pepper","icon-Petrol","icon-Petronas-Tower","icon-Philipines","icon-Phone-2","icon-Phone-3","icon-Phone-3G","icon-Phone-4G","icon-Phone-Simcard","icon-Phone-SMS","icon-Phone-Wifi","icon-Phone","icon-Photo-2","icon-Photo-3","icon-Photo-Album","icon-Photo-Album2","icon-Photo-Album3","icon-Photo","icon-Photos","icon-Physics","icon-Pi","icon-Piano","icon-Picasa","icon-Pie-Chart","icon-Pie-Chart2","icon-Pie-Chart3","icon-Pilates-2","icon-Pilates-3","icon-Pilates","icon-Pilot","icon-Pinch","icon-Ping-Pong","icon-Pinterest","icon-Pipe","icon-Pipette","icon-Piramids","icon-Pisces-2","icon-Pisces","icon-Pizza-Slice","icon-Pizza","icon-Plane-2","icon-Plane","icon-Plant","icon-Plasmid","icon-Plaster","icon-Plastic-CupPhone","icon-Plastic-CupPhone2","icon-Plate","icon-Plates","icon-Plaxo","icon-Play-Music","icon-Plug-In","icon-Plug-In2","icon-Plurk","icon-Pointer","icon-Poland","icon-Police-Man","icon-Police-Station","icon-Police-Woman","icon-Police","icon-Polo-Shirt","icon-Portrait","icon-Portugal","icon-Post-Mail","icon-Post-Mail2","icon-Post-Office","icon-Post-Sign","icon-Post-Sign2ways","icon-Posterous","icon-Pound-Sign","icon-Pound-Sign2","icon-Pound","icon-Power-2","icon-Power-3","icon-Power-Cable","icon-Power-Station","icon-Power","icon-Prater","icon-Present","icon-Presents","icon-Press","icon-Preview","icon-Previous","icon-Pricing","icon-Printer","icon-Professor","icon-Profile","icon-Project","icon-Projector-2","icon-Projector","icon-Pulse","icon-Pumpkin","icon-Punk","icon-Punker","icon-Puzzle","icon-QIK","icon-QR-Code","icon-Queen-2","icon-Queen","icon-Quill-2","icon-Quill-3","icon-Quill","icon-Quotes-2","icon-Quotes","icon-Radio","icon-Radioactive","icon-Rafting","icon-Rain-Drop","icon-Rainbow-2","icon-Rainbow","icon-Ram","icon-Razzor-Blade","icon-Receipt-2","icon-Receipt-3","icon-Receipt-4","icon-Receipt","icon-Record2","icon-Record-3","icon-Record-Music","icon-Record","icon-Recycling-2","icon-Recycling","icon-Reddit","icon-Redhat","icon-Redirect","icon-Redo","icon-Reel","icon-Refinery","icon-Refresh-Window","icon-Refresh","icon-Reload-2","icon-Reload-3","icon-Reload","icon-Remote-Controll","icon-Remote-Controll2","icon-Remove-Bag","icon-Remove-Basket","icon-Remove-Cart","icon-Remove-File","icon-Remove-User","icon-Remove-Window","icon-Remove","icon-Rename","icon-Repair","icon-Repeat-2","icon-Repeat-3","icon-Repeat-4","icon-Repeat-5","icon-Repeat-6","icon-Repeat-7","icon-Repeat","icon-Reset","icon-Resize","icon-Restore-Window","icon-Retouching","icon-Retro-Camera","icon-Retro","icon-Retweet","icon-Reverbnation","icon-Rewind","icon-RGB","icon-Ribbon-2","icon-Ribbon-3","icon-Ribbon","icon-Right-2","icon-Right-3","icon-Right-4","icon-Right-ToLeft","icon-Right","icon-Road-2","icon-Road-3","icon-Road","icon-Robot-2","icon-Robot","icon-Rock-andRoll","icon-Rocket","icon-Roller","icon-Roof","icon-Rook","icon-Rotate-Gesture","icon-Rotate-Gesture2","icon-Rotate-Gesture3","icon-Rotation-390","icon-Rotation","icon-Router-2","icon-Router","icon-RSS","icon-Ruler-2","icon-Ruler","icon-Running-Shoes","icon-Running","icon-Safari","icon-Safe-Box","icon-Safe-Box2","icon-Safety-PinClose","icon-Safety-PinOpen","icon-Sagittarus-2","icon-Sagittarus","icon-Sailing-Ship","icon-Sand-watch","icon-Sand-watch2","icon-Santa-Claus","icon-Santa-Claus2","icon-Santa-onSled","icon-Satelite-2","icon-Satelite","icon-Save-Window","icon-Save","icon-Saw","icon-Saxophone","icon-Scale","icon-Scarf","icon-Scissor","icon-Scooter-Front","icon-Scooter","icon-Scorpio-2","icon-Scorpio","icon-Scotland","icon-Screwdriver","icon-Scroll-Fast","icon-Scroll","icon-Scroller-2","icon-Scroller","icon-Sea-Dog","icon-Search-onCloud","icon-Search-People","icon-secound","icon-secound2","icon-Security-Block","icon-Security-Bug","icon-Security-Camera","icon-Security-Check","icon-Security-Settings","icon-Security-Smiley","icon-Securiy-Remove","icon-Seed","icon-Selfie","icon-Serbia","icon-Server-2","icon-Server","icon-Servers","icon-Settings-Window","icon-Sewing-Machine","icon-Sexual","icon-Share-onCloud","icon-Share-Window","icon-Share","icon-Sharethis","icon-Shark","icon-Sheep","icon-Sheriff-Badge","icon-Shield","icon-Ship-2","icon-Ship","icon-Shirt","icon-Shoes-2","icon-Shoes-3","icon-Shoes","icon-Shop-2","icon-Shop-3","icon-Shop-4","icon-Shop","icon-Shopping-Bag","icon-Shopping-Basket","icon-Shopping-Cart","icon-Short-Pants","icon-Shoutwire","icon-Shovel","icon-Shuffle-2","icon-Shuffle-3","icon-Shuffle-4","icon-Shuffle","icon-Shutter","icon-Sidebar-Window","icon-Signal","icon-Singapore","icon-Skate-Shoes","icon-Skateboard-2","icon-Skateboard","icon-Skeleton","icon-Ski","icon-Skirt","icon-Skrill","icon-Skull","icon-Skydiving","icon-Skype","icon-Sled-withGifts","icon-Sled","icon-Sleeping","icon-Sleet","icon-Slippers","icon-Smart","icon-Smartphone-2","icon-Smartphone-3","icon-Smartphone-4","icon-Smartphone-Secure","icon-Smartphone","icon-Smile","icon-Smoking-Area","icon-Smoking-Pipe","icon-Snake","icon-Snorkel","icon-Snow-2","icon-Snow-Dome","icon-Snow-Storm","icon-Snow","icon-Snowflake-2","icon-Snowflake-3","icon-Snowflake-4","icon-Snowflake","icon-Snowman","icon-Soccer-Ball","icon-Soccer-Shoes","icon-Socks","icon-Solar","icon-Sound-Wave","icon-Sound","icon-Soundcloud","icon-Soup","icon-South-Africa","icon-Space-Needle","icon-Spain","icon-Spam-Mail","icon-Speach-Bubble","icon-Speach-Bubble2","icon-Speach-Bubble3","icon-Speach-Bubble4","icon-Speach-Bubble5","icon-Speach-Bubble6","icon-Speach-Bubble7","icon-Speach-Bubble8","icon-Speach-Bubble9","icon-Speach-Bubble10","icon-Speach-Bubble11","icon-Speach-Bubble12","icon-Speach-Bubble13","icon-Speach-BubbleAsking","icon-Speach-BubbleComic","icon-Speach-BubbleComic2","icon-Speach-BubbleComic3","icon-Speach-BubbleComic4","icon-Speach-BubbleDialog","icon-Speach-Bubbles","icon-Speak-2","icon-Speak","icon-Speaker-2","icon-Speaker","icon-Spell-Check","icon-Spell-CheckABC","icon-Spermium","icon-Spider","icon-Spiderweb","icon-Split-FourSquareWindow","icon-Split-Horizontal","icon-Split-Horizontal2Window","icon-Split-Vertical","icon-Split-Vertical2","icon-Split-Window","icon-Spoder","icon-Spoon","icon-Sport-Mode","icon-Sports-Clothings1","icon-Sports-Clothings2","icon-Sports-Shirt","icon-Spot","icon-Spray","icon-Spread","icon-Spring","icon-Spurl","icon-Spy","icon-Squirrel","icon-SSL","icon-St-BasilsCathedral","icon-St-PaulsCathedral","icon-Stamp-2","icon-Stamp","icon-Stapler","icon-Star-Track","icon-Star","icon-Starfish","icon-Start2","icon-Start-3","icon-Start-ways","icon-Start","icon-Statistic","icon-Stethoscope","icon-stop--2","icon-Stop-Music","icon-Stop","icon-Stopwatch-2","icon-Stopwatch","icon-Storm","icon-Street-View","icon-Street-View2","icon-Strikethrough-Text","icon-Stroller","icon-Structure","icon-Student-Female","icon-Student-Hat","icon-Student-Hat2","icon-Student-Male","icon-Student-MaleFemale","icon-Students","icon-Studio-Flash","icon-Studio-Lightbox","icon-Stumbleupon","icon-Suit","icon-Suitcase","icon-Sum-2","icon-Sum","icon-Summer","icon-Sun-CloudyRain","icon-Sun","icon-Sunglasses-2","icon-Sunglasses-3","icon-Sunglasses-Smiley","icon-Sunglasses-Smiley2","icon-Sunglasses-W","icon-Sunglasses-W2","icon-Sunglasses-W3","icon-Sunglasses","icon-Sunrise","icon-Sunset","icon-Superman","icon-Support","icon-Surprise","icon-Sushi","icon-Sweden","icon-Swimming-Short","icon-Swimming","icon-Swimmwear","icon-Switch","icon-Switzerland","icon-Sync-Cloud","icon-Sync","icon-Synchronize-2","icon-Synchronize","icon-T-Shirt","icon-Tablet-2","icon-Tablet-3","icon-Tablet-Orientation","icon-Tablet-Phone","icon-Tablet-Secure","icon-Tablet-Vertical","icon-Tablet","icon-Tactic","icon-Tag-2","icon-Tag-3","icon-Tag-4","icon-Tag-5","icon-Tag","icon-Taj-Mahal","icon-Talk-Man","icon-Tap","icon-Target-Market","icon-Target","icon-Taurus-2","icon-Taurus","icon-Taxi-2","icon-Taxi-Sign","icon-Taxi","icon-Teacher","icon-Teapot","icon-Technorati","icon-Teddy-Bear","icon-Tee-Mug","icon-Telephone-2","icon-Telephone","icon-Telescope","icon-Temperature-2","icon-Temperature-3","icon-Temperature","icon-Temple","icon-Tennis-Ball","icon-Tennis","icon-Tent","icon-Test-Tube","icon-Test-Tube2","icon-Testimonal","icon-Text-Box","icon-Text-Effect","icon-Text-HighlightColor","icon-Text-Paragraph","icon-Thailand","icon-The-WhiteHouse","icon-This-SideUp","icon-Thread","icon-Three-ArrowFork","icon-Three-Fingers","icon-Three-FingersDrag","icon-Three-FingersDrag2","icon-Three-FingersTouch","icon-Thumb","icon-Thumbs-DownSmiley","icon-Thumbs-UpSmiley","icon-Thunder","icon-Thunderstorm","icon-Ticket","icon-Tie-2","icon-Tie-3","icon-Tie-4","icon-Tie","icon-Tiger","icon-Time-Backup","icon-Time-Bomb","icon-Time-Clock","icon-Time-Fire","icon-Time-Machine","icon-Time-Window","icon-Timer-2","icon-Timer","icon-To-Bottom","icon-To-Bottom2","icon-To-Left","icon-To-Right","icon-To-Top","icon-To-Top2","icon-Token-","icon-Tomato","icon-Tongue","icon-Tooth-2","icon-Tooth","icon-Top-ToBottom","icon-Touch-Window","icon-Tourch","icon-Tower-2","icon-Tower-Bridge","icon-Tower","icon-Trace","icon-Tractor","icon-traffic-Light","icon-Traffic-Light2","icon-Train-2","icon-Train","icon-Tram","icon-Transform-2","icon-Transform-3","icon-Transform-4","icon-Transform","icon-Trash-withMen","icon-Tree-2","icon-Tree-3","icon-Tree-4","icon-Tree-5","icon-Tree","icon-Trekking","icon-Triangle-ArrowDown","icon-Triangle-ArrowLeft","icon-Triangle-ArrowRight","icon-Triangle-ArrowUp","icon-Tripod-2","icon-Tripod-andVideo","icon-Tripod-withCamera","icon-Tripod-withGopro","icon-Trophy-2","icon-Trophy","icon-Truck","icon-Trumpet","icon-Tumblr","icon-Turkey","icon-Turn-Down","icon-Turn-Down2","icon-Turn-DownFromLeft","icon-Turn-DownFromRight","icon-Turn-Left","icon-Turn-Left3","icon-Turn-Right","icon-Turn-Right3","icon-Turn-Up","icon-Turn-Up2","icon-Turtle","icon-Tuxedo","icon-TV","icon-Twister","icon-Twitter-2","icon-Twitter","icon-Two-Fingers","icon-Two-FingersDrag","icon-Two-FingersDrag2","icon-Two-FingersScroll","icon-Two-FingersTouch","icon-Two-Windows","icon-Type-Pass","icon-Ukraine","icon-Umbrela","icon-Umbrella-2","icon-Umbrella-3","icon-Under-LineText","icon-Undo","icon-United-Kingdom","icon-United-States","icon-University-2","icon-University","icon-Unlike-2","icon-Unlike","icon-Unlock-2","icon-Unlock-3","icon-Unlock","icon-Up--Down","icon-Up--Down3","icon-Up-2","icon-Up-3","icon-Up-4","icon-Up","icon-Upgrade","icon-Upload-2","icon-Upload-toCloud","icon-Upload-Window","icon-Upload","icon-Uppercase-Text","icon-Upward","icon-URL-Window","icon-Usb-2","icon-Usb-Cable","icon-Usb","icon-User","icon-Ustream","icon-Vase","icon-Vector-2","icon-Vector-3","icon-Vector-4","icon-Vector-5","icon-Vector","icon-Venn-Diagram","icon-Vest-2","icon-Vest","icon-Viddler","icon-Video-2","icon-Video-3","icon-Video-4","icon-Video-5","icon-Video-6","icon-Video-GameController","icon-Video-Len","icon-Video-Len2","icon-Video-Photographer","icon-Video-Tripod","icon-Video","icon-Vietnam","icon-View-Height","icon-View-Width","icon-Vimeo","icon-Virgo-2","icon-Virgo","icon-Virus-2","icon-Virus-3","icon-Virus","icon-Visa","icon-Voice","icon-Voicemail","icon-Volleyball","icon-Volume-Down","icon-Volume-Up","icon-VPN","icon-Wacom-Tablet","icon-Waiter","icon-Walkie-Talkie","icon-Wallet-2","icon-Wallet-3","icon-Wallet","icon-Warehouse","icon-Warning-Window","icon-Watch-2","icon-Watch-3","icon-Watch","icon-Wave-2","icon-Wave","icon-Webcam","icon-weight-Lift","icon-Wheelbarrow","icon-Wheelchair","icon-Width-Window","icon-Wifi-2","icon-Wifi-Keyboard","icon-Wifi","icon-Wind-Turbine","icon-Windmill","icon-Window-2","icon-Window","icon-Windows-2","icon-Windows-Microsoft","icon-Windows","icon-Windsock","icon-Windy","icon-Wine-Bottle","icon-Wine-Glass","icon-Wink","icon-Winter-2","icon-Winter","icon-Wireless","icon-Witch-Hat","icon-Witch","icon-Wizard","icon-Wolf","icon-Woman-Sign","icon-WomanMan","icon-Womans-Underwear","icon-Womans-Underwear2","icon-Women","icon-Wonder-Woman","icon-Wordpress","icon-Worker-Clothes","icon-Worker","icon-Wrap-Text","icon-Wreath","icon-Wrench","icon-X-Box","icon-X-ray","icon-Xanga","icon-Xing","icon-Yacht","icon-Yahoo-Buzz","icon-Yahoo","icon-Yelp","icon-Yes","icon-Ying-Yang","icon-Youtube","icon-Z-A","icon-Zebra","icon-Zombie","icon-Zoom-Gesture","icon-Zootool"],
				"notRequiredBy":".icon[class*='socicon'], .icon[class*='stack-']"
			},
	    {
	      "name":"Material Icons",
	      "url":"http://goo.gl/rKlODf",
	      "headString":"",
	      "type":"text",
	      "iconPrefix":"",
	      "iconClass":"material-icons",
	      "icons":["3d_rotation","ac_unit","access_alarm","access_alarms","access_time","accessibility","accessible","account_balance","account_balance_wallet","account_box","account_circle","adb","add","add_a_photo","add_alarm","add_alert","add_box","add_circle","add_circle_outline","add_location","add_shopping_cart","add_to_photos","add_to_queue","adjust","airline_seat_flat","airline_seat_flat_angled","airline_seat_individual_suite","airline_seat_legroom_extra","airline_seat_legroom_normal","airline_seat_legroom_reduced","airline_seat_recline_extra","airline_seat_recline_normal","airplanemode_active","airplanemode_inactive","airplay","airport_shuttle","alarm","alarm_add","alarm_off","alarm_on","album","all_inclusive","all_out","android","announcement","apps","archive","arrow_back","arrow_downward","arrow_drop_down","arrow_drop_down_circle","arrow_drop_up","arrow_forward","arrow_upward","art_track","aspect_ratio","assessment","assignment","assignment_ind","assignment_late","assignment_return","assignment_returned","assignment_turned_in","assistant","assistant_photo","attach_file","attach_money","attachment","audiotrack","autorenew","av_timer","backspace","backup","battery_alert","battery_charging_full","battery_full","battery_std","battery_unknown","beach_access","beenhere","block","bluetooth","bluetooth_audio","bluetooth_connected","bluetooth_disabled","bluetooth_searching","blur_circular","blur_linear","blur_off","blur_on","book","bookmark","bookmark_border","border_all","border_bottom","border_clear","border_color","border_horizontal","border_inner","border_left","border_outer","border_right","border_style","border_top","border_vertical","branding_watermark","brightness_1","brightness_2","brightness_3","brightness_4","brightness_5","brightness_6","brightness_7","brightness_auto","brightness_high","brightness_low","brightness_medium","broken_image","brush","bubble_chart","bug_report","build","burst_mode","business","business_center","cached","cake","call","call_end","call_made","call_merge","call_missed","call_missed_outgoing","call_received","call_split","call_to_action","camera","camera_alt","camera_enhance","camera_front","camera_rear","camera_roll","cancel","card_giftcard","card_membership","card_travel","casino","cast","cast_connected","center_focus_strong","center_focus_weak","change_history","chat","chat_bubble","chat_bubble_outline","check","check_box","check_box_outline_blank","check_circle","chevron_left","chevron_right","child_care","child_friendly","chrome_reader_mode","class","clear","clear_all","close","closed_caption","cloud","cloud_circle","cloud_done","cloud_download","cloud_off","cloud_queue","cloud_upload","code","collections","collections_bookmark","color_lens","colorize","comment","compare","compare_arrows","computer","confirmation_number","contact_mail","contact_phone","contacts","content_copy","content_cut","content_paste","control_point","control_point_duplicate","copyright","create","create_new_folder","credit_card","crop","crop_16_9","crop_3_2","crop_5_4","crop_7_5","crop_din","crop_free","crop_landscape","crop_original","crop_portrait","crop_rotate","crop_square","dashboard","data_usage","date_range","dehaze","delete","delete_forever","delete_sweep","description","desktop_mac","desktop_windows","details","developer_board","developer_mode","device_hub","devices","devices_other","dialer_sip","dialpad","directions","directions_bike","directions_boat","directions_bus","directions_car","directions_railway","directions_run","directions_subway","directions_transit","directions_walk","disc_full","dns","do_not_disturb","do_not_disturb_alt","do_not_disturb_off","do_not_disturb_on","dock","domain","done","done_all","donut_large","donut_small","drafts","drag_handle","drive_eta","dvr","edit","edit_location","eject","email","enhanced_encryption","equalizer","error","error_outline","euro_symbol","ev_station","event","event_available","event_busy","event_note","event_seat","exit_to_app","expand_less","expand_more","explicit","explore","exposure","exposure_neg_1","exposure_neg_2","exposure_plus_1","exposure_plus_2","exposure_zero","extension","face","fast_forward","fast_rewind","favorite","favorite_border","featured_play_list","featured_video","feedback","fiber_dvr","fiber_manual_record","fiber_new","fiber_pin","fiber_smart_record","file_download","file_upload","filter","filter_1","filter_2","filter_3","filter_4","filter_5","filter_6","filter_7","filter_8","filter_9","filter_9_plus","filter_b_and_w","filter_center_focus","filter_drama","filter_frames","filter_hdr","filter_list","filter_none","filter_tilt_shift","filter_vintage","find_in_page","find_replace","fingerprint","first_page","fitness_center","flag","flare","flash_auto","flash_off","flash_on","flight","flight_land","flight_takeoff","flip","flip_to_back","flip_to_front","folder","folder_open","folder_shared","folder_special","font_download","format_align_center","format_align_justify","format_align_left","format_align_right","format_bold","format_clear","format_color_fill","format_color_reset","format_color_text","format_indent_decrease","format_indent_increase","format_italic","format_line_spacing","format_list_bulleted","format_list_numbered","format_paint","format_quote","format_shapes","format_size","format_strikethrough","format_textdirection_l_to_r","format_textdirection_r_to_l","format_underlined","forum","forward","forward_10","forward_30","forward_5","free_breakfast","fullscreen","fullscreen_exit","functions","g_translate","gamepad","games","gavel","gesture","get_app","gif","golf_course","gps_fixed","gps_not_fixed","gps_off","grade","gradient","grain","graphic_eq","grid_off","grid_on","group","group_add","group_work","hd","hdr_off","hdr_on","hdr_strong","hdr_weak","headset","headset_mic","healing","hearing","help","help_outline","high_quality","highlight","highlight_off","history","home","hot_tub","hotel","hourglass_empty","hourglass_full","http","https","image","image_aspect_ratio","import_contacts","import_export","important_devices","inbox","indeterminate_check_box","info","info_outline","input","insert_chart","insert_comment","insert_drive_file","insert_emoticon","insert_invitation","insert_link","insert_photo","invert_colors","invert_colors_off","iso","keyboard","keyboard_arrow_down","keyboard_arrow_left","keyboard_arrow_right","keyboard_arrow_up","keyboard_backspace","keyboard_capslock","keyboard_hide","keyboard_return","keyboard_tab","keyboard_voice","kitchen","label","label_outline","landscape","language","laptop","laptop_chromebook","laptop_mac","laptop_windows","last_page","launch","layers","layers_clear","leak_add","leak_remove","lens","library_add","library_books","library_music","lightbulb_outline","line_style","line_weight","linear_scale","link","linked_camera","list","live_help","live_tv","local_activity","local_airport","local_atm","local_bar","local_cafe","local_car_wash","local_convenience_store","local_dining","local_drink","local_florist","local_gas_station","local_grocery_store","local_hospital","local_hotel","local_laundry_service","local_library","local_mall","local_movies","local_offer","local_parking","local_pharmacy","local_phone","local_pizza","local_play","local_post_office","local_printshop","local_see","local_shipping","local_taxi","location_city","location_disabled","location_off","location_on","location_searching","lock","lock_open","lock_outline","looks","looks_3","looks_4","looks_5","looks_6","looks_one","looks_two","loop","loupe","low_priority","loyalty","mail","mail_outline","map","markunread","markunread_mailbox","memory","menu","merge_type","message","mic","mic_none","mic_off","mms","mode_comment","mode_edit","monetization_on","money_off","monochrome_photos","mood","mood_bad","more","more_horiz","more_vert","motorcycle","mouse","move_to_inbox","movie","movie_creation","movie_filter","multiline_chart","music_note","music_video","my_location","nature","nature_people","navigate_before","navigate_next","navigation","near_me","network_cell","network_check","network_locked","network_wifi","new_releases","next_week","nfc","no_encryption","no_sim","not_interested","note","note_add","notifications","notifications_active","notifications_none","notifications_off","notifications_paused","offline_pin","ondemand_video","opacity","open_in_browser","open_in_new","open_with","pages","pageview","palette","pan_tool","panorama","panorama_fish_eye","panorama_horizontal","panorama_vertical","panorama_wide_angle","party_mode","pause","pause_circle_filled","pause_circle_outline","payment","people","people_outline","perm_camera_mic","perm_contact_calendar","perm_data_setting","perm_device_information","perm_identity","perm_media","perm_phone_msg","perm_scan_wifi","person","person_add","person_outline","person_pin","person_pin_circle","personal_video","pets","phone","phone_android","phone_bluetooth_speaker","phone_forwarded","phone_in_talk","phone_iphone","phone_locked","phone_missed","phone_paused","phonelink","phonelink_erase","phonelink_lock","phonelink_off","phonelink_ring","phonelink_setup","photo","photo_album","photo_camera","photo_filter","photo_library","photo_size_select_actual","photo_size_select_large","photo_size_select_small","picture_as_pdf","picture_in_picture","picture_in_picture_alt","pie_chart","pie_chart_outlined","pin_drop","place","play_arrow","play_circle_filled","play_circle_outline","play_for_work","playlist_add","playlist_add_check","playlist_play","plus_one","poll","polymer","pool","portable_wifi_off","portrait","power","power_input","power_settings_new","pregnant_woman","present_to_all","print","priority_high","public","publish","query_builder","question_answer","queue","queue_music","queue_play_next","radio","radio_button_checked","radio_button_unchecked","rate_review","receipt","recent_actors","record_voice_over","redeem","redo","refresh","remove","remove_circle","remove_circle_outline","remove_from_queue","remove_red_eye","remove_shopping_cart","reorder","repeat","repeat_one","replay","replay_10","replay_30","replay_5","reply","reply_all","report","report_problem","restaurant","restaurant_menu","restore","restore_page","ring_volume","room","room_service","rotate_90_degrees_ccw","rotate_left","rotate_right","rounded_corner","router","rowing","rss_feed","rv_hookup","satellite","save","scanner","schedule","school","screen_lock_landscape","screen_lock_portrait","screen_lock_rotation","screen_rotation","screen_share","sd_card","sd_storage","search","security","select_all","send","sentiment_dissatisfied","sentiment_neutral","sentiment_satisfied","sentiment_very_dissatisfied","sentiment_very_satisfied","settings","settings_applications","settings_backup_restore","settings_bluetooth","settings_brightness","settings_cell","settings_ethernet","settings_input_antenna","settings_input_component","settings_input_composite","settings_input_hdmi","settings_input_svideo","settings_overscan","settings_phone","settings_power","settings_remote","settings_system_daydream","settings_voice","share","shop","shop_two","shopping_basket","shopping_cart","short_text","show_chart","shuffle","signal_cellular_4_bar","signal_cellular_connected_no_internet_4_bar","signal_cellular_no_sim","signal_cellular_null","signal_cellular_off","signal_wifi_4_bar","signal_wifi_4_bar_lock","signal_wifi_off","sim_card","sim_card_alert","skip_next","skip_previous","slideshow","slow_motion_video","smartphone","smoke_free","smoking_rooms","sms","sms_failed","snooze","sort","sort_by_alpha","spa","space_bar","speaker","speaker_group","speaker_notes","speaker_notes_off","speaker_phone","spellcheck","star","star_border","star_half","stars","stay_current_landscape","stay_current_portrait","stay_primary_landscape","stay_primary_portrait","stop","stop_screen_share","storage","store","store_mall_directory","straighten","streetview","strikethrough_s","style","subdirectory_arrow_left","subdirectory_arrow_right","subject","subscriptions","subtitles","subway","supervisor_account","surround_sound","swap_calls","swap_horiz","swap_vert","swap_vertical_circle","switch_camera","switch_video","sync","sync_disabled","sync_problem","system_update","system_update_alt","tab","tab_unselected","tablet","tablet_android","tablet_mac","tag_faces","tap_and_play","terrain","text_fields","text_format","textsms","texture","theaters","thumb_down","thumb_up","thumbs_up_down","time_to_leave","timelapse","timeline","timer","timer_10","timer_3","timer_off","title","toc","today","toll","tonality","touch_app","toys","track_changes","traffic","train","tram","transfer_within_a_station","transform","translate","trending_down","trending_flat","trending_up","tune","turned_in","turned_in_not","tv","unarchive","undo","unfold_less","unfold_more","update","usb","verified_user","vertical_align_bottom","vertical_align_center","vertical_align_top","vibration","video_call","video_label","video_library","videocam","videocam_off","videogame_asset","view_agenda","view_array","view_carousel","view_column","view_comfy","view_compact","view_day","view_headline","view_list","view_module","view_quilt","view_stream","view_week","vignette","visibility","visibility_off","voice_chat","voicemail","volume_down","volume_mute","volume_off","volume_up","vpn_key","vpn_lock","wallpaper","warning","watch","watch_later","wb_auto","wb_cloudy","wb_incandescent","wb_iridescent","wb_sunny","wc","web","web_asset","weekend","whatshot","widgets","wifi","wifi_lock","wifi_tethering","work","wrap_text","youtube_searched_for","zoom_in","zoom_out","zoom_out_map"],
	      "notRequiredBy":".icon[class*='socicon'], .icon[class*='stack-'], [class*='icon-']"
	    },
			{
				"name":"Socicon",
				"url":"http://goo.gl/hdiFGw",
				"headString":"",
				"type":"class",
	      "iconPrefix":"socicon-",
				"iconClass":"",
				"icons":["socicon-500px","socicon-8tracks","socicon-airbnb","socicon-alliance","socicon-amazon","socicon-amplement","socicon-android","socicon-angellist","socicon-apple","socicon-appnet","socicon-baidu","socicon-bandcamp","socicon-battlenet","socicon-beam","socicon-bebee","socicon-bebo","socicon-behance","socicon-blizzard","socicon-blogger","socicon-bloglovin","socicon-buffer","socicon-chrome","socicon-coderwall","socicon-curse","socicon-dailymotion","socicon-deezer","socicon-delicious","socicon-deviantart","socicon-diablo","socicon-digg","socicon-discord","socicon-disqus","socicon-douban","socicon-draugiem","socicon-dribbble","socicon-drupal","socicon-ebay","socicon-ello","socicon-endomodo","socicon-envato","socicon-etsy","socicon-facebook","socicon-feedburner","socicon-filmweb","socicon-firefox","socicon-flattr","socicon-flickr","socicon-formulr","socicon-forrst","socicon-foursquare","socicon-friendfeed","socicon-gamejolt","socicon-gamewisp","socicon-github","socicon-goodreads","socicon-google","socicon-googlegroups","socicon-googlephotos","socicon-googleplus","socicon-googlescholar","socicon-grooveshark","socicon-hackerrank","socicon-hearthstone","socicon-hellocoton","socicon-heroes","socicon-hitbox","socicon-horde","socicon-houzz","socicon-icq","socicon-identica","socicon-imdb","socicon-instagram","socicon-issuu","socicon-istock","socicon-itunes","socicon-keybase","socicon-lanyrd","socicon-lastfm","socicon-line","socicon-linkedin","socicon-livejournal","socicon-lyft","socicon-macos","socicon-mail","socicon-medium","socicon-meetup","socicon-mixcloud","socicon-modelmayhem","socicon-mumble","socicon-myspace","socicon-newsvine","socicon-nintendo","socicon-npm","socicon-odnoklassniki","socicon-openid","socicon-opera","socicon-outlook","socicon-overwatch","socicon-patreon","socicon-paypal","socicon-periscope","socicon-persona","socicon-pinterest","socicon-play","socicon-player","socicon-playstation","socicon-pocket","socicon-qq","socicon-quora","socicon-raidcall","socicon-ravelry","socicon-reddit","socicon-renren","socicon-researchgate","socicon-residentadvisor","socicon-reverbnation","socicon-rss","socicon-sharethis","socicon-skype","socicon-slideshare","socicon-smugmug","socicon-snapchat","socicon-songkick","socicon-soundcloud","socicon-spotify","socicon-spreadshirt","socicon-stackexchange","socicon-stackoverflow","socicon-starcraft","socicon-stayfriends","socicon-steam","socicon-storehouse","socicon-strava","socicon-streamjar","socicon-stumbleupon","socicon-swarm","socicon-teamspeak","socicon-teamviewer","socicon-technorati","socicon-telegram","socicon-trello","socicon-tripadvisor","socicon-tripit","socicon-triplej","socicon-tumblr","socicon-tunein","socicon-twitch","socicon-twitter","socicon-uber","socicon-ventrilo","socicon-viadeo","socicon-viber","socicon-viewbug","socicon-vimeo","socicon-vine","socicon-vkontakte","socicon-warcraft","socicon-wechat","socicon-weibo","socicon-whatsapp","socicon-wikipedia","socicon-windows","socicon-wordpress","socicon-wykop","socicon-xbox","socicon-xing","socicon-yahoo","socicon-yammer","socicon-yandex","socicon-yelp","socicon-younow","socicon-youtube","socicon-zapier","socicon-zerply","socicon-zomato","socicon-zynga"],
				"requiredBy":"[class*='socicon-']"
			},
			{
				"name":"Stack Interface",
				"url":"",
				"headString":"",
				"type":"class",
	      "iconPrefix":"stack-",
				"iconClass":"",
				"icons":["stack-down-open-big","stack-left-open-big","stack-right-open-big","stack-up-open-big","stack-basket","stack-search","stack-down-dir","stack-left-dir","stack-right-dir","stack-up-dir","stack-down-open","stack-left-open","stack-right-open","stack-up-open","stack-menu","stack-users","stack-publish","stack-trash","stack-bell","stack-cog","stack-plus-circled","stack-dot-3"],
				"requiredBy":"[class*='stack-']"
			}
		]
	}
</script>

<script id="vpa" type="application/json">
	{
		"dynamicScripts":[ 
	        {
	            "scriptRef":"&lt;script src=\"js/flickity.min.js\"&gt;&lt;/script&gt;",
	            "requiredBy":".slider, .tweets-slider"
	        },
	        {
	            "scriptRef":"&lt;script src=\"js/lightbox.min.js\"&gt;&lt;/script&gt;",
	            "requiredBy":"[data-lightbox]"
	        },
	        {
	            "scriptRef":"&lt;script src=\"js/isotope.min.js\"&gt;&lt;/script&gt;",
	            "requiredBy":".masonry"
	        },
	        {
	            "scriptRef":"&lt;script src=\"js/twitterfetcher.min.js\"&gt;&lt;/script&gt;",
	            "requiredBy":".tweets-feed"
	        },
	        {
	            "scriptRef":"&lt;script src=\"js/ytplayer.min.js\"&gt;&lt;/script&gt;",
	            "requiredBy":".youtube-background"
	        },
	        {
	            "scriptRef":"&lt;script src=\"js/typed.min.js\"&gt;&lt;/script&gt;",
	            "requiredBy":".typed-headline"
	        },
	        {
	            "scriptRef":"&lt;script src=\"js/spectragram.min.js\"&gt;&lt;/script&gt;",
	            "requiredBy":".instafeed"
	        },
	        {
	            "scriptRef":"&lt;script src=\"js/granim.min.js\"&gt;&lt;/script&gt;",
	            "requiredBy":"[data-gradient-bg]"
	        }
		]
	}
</script>

<script id="vpb" type="application/json">
	{
	   "dynamicCSS":[
	      {
	         "styleRef":"&lt;link href=\"css/flickity.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" /&gt;",
	         "requiredBy":".slider, .tweets-slider"
	      },
	      {
	         "styleRef":"&lt;link href=\"css/lightbox.min.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" /&gt;",
	         "requiredBy":"[data-lightbox]"
	      }
	   ]
	}
</script>