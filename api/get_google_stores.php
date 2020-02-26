<?php
require_once('include/config.php');
//require_once('include/init.php');

if($_REQUEST['current_latlng']!='')
	$current_latlng = $_REQUEST['current_latlng'];
else
	$err = $lang["REQ_PARA"] .'current_latlng';

$language = ($language!='')?$language:'ar';

if($_REQUEST['search_txt']!='') {

 $search_txt= urlencode($_REQUEST['search_txt']);
 //if(strlen($search_txt) != mb_strlen($search_txt, 'utf-8')) {
if (strlen($search_txt) != strlen(utf8_decode($search_txt))) {
    $lang = '&language=ar';
 }
 else {
    $lang = '&language=en';
 }
}

/*if($err!='')
{
	$jsonArray['Success']='0';
	$jsonArray['Message']=$err;
	show_output($jsonArray);
}	*/

// &opennow=true

if(!$dev)
{
	if($search_txt!='')
	$url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=".$search_txt."&location=".$current_latlng. $lang ."&radius=20000&key=".GOOGLE_API_KEY;
	else
	$url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=supermarket&location=".$current_latlng. $lang ."&radius=10000&key=".GOOGLE_API_KEY;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$result = curl_exec($ch);
	$res = json_decode($result);
	if($res->status == 'ZERO_RESULTS' && $search_txt!='')
	{
		$url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?type=supermarket&name=".$search_txt."&location=".$current_latlng. $lang ."&radius=20000&key=".GOOGLE_API_KEY;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
	}
	echo $result;
}
else
{
	$result = '{
   "html_attributions" : [],
   "next_page_token" : "CrQCJAEAACUN9ahfFJDft50FTY6p6WGATHunsqBlT_HEyp3k_D6dpydYXKwxqBHPiUJNQjBsfjJAxX222Jdp_TnKhh7oQvY0GifDpz9O1W-Ejlase-Z4vj106M7MI74USR3VlE5wlSxXJ3tFQpgIUMYbpbQCXtLiZQt56UaIXLRfyNkxMigh2XBQQJY8rcRa-S8cI8Sra4w17h2vdiLq30gEeCG_PBEr0SqA6OT9WLsybyYoy7vDcY4QpabnmPejeYNp9wKvrq6pIJMhKLWJqNA0NwYc-qwAoXTeW3z31rK4NVVjRXHgOcAiymCrquQ8G7gxCd-OaK96SVhwAKbznxEhg70DU2w6vVilQWHuzE7rJi-cG76Er0xNYPPysbtn04hfAvIvWmUtvMG_iUfXtGzn76pu4koSECBC8jU408_AedYH9UMkoNsaFDFeRogvgvoRiDCmKKeMWaNFcnWr",
   "results" : [
      {
         "formatted_address" : "7, Jagatnagar, Bapunagar, Opposite Dinesh Chembre, Bapunagar, Ahmedabad, Gujarat 380024, India",
         "geometry" : {
            "location" : {
               "lat" : 23.0321153,
               "lng" : 72.62920319999999
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.03347172989272,
                  "lng" : 72.63052282989273
               },
               "southwest" : {
                  "lat" : 23.03077207010728,
                  "lng" : 72.62782317010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "985429e6670704bef9fe874134b6cb19207c00c8",
         "name" : "Super Market",
         "place_id" : "ChIJ3Tt4r4-GXjkR_eXQQQBc0nU",
         "rating" : 4.5,
         "reference" : "ChIJ3Tt4r4-GXjkR_eXQQQBc0nU",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 2
      },
      {
         "formatted_address" : "Urjanagar 1, Kudasan, Gandhinagar, Gujarat 382421, India",
         "geometry" : {
            "location" : {
               "lat" : 23.1787936,
               "lng" : 72.62894919999999
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.18014972989272,
                  "lng" : 72.63037337989272
               },
               "southwest" : {
                  "lat" : 23.17745007010728,
                  "lng" : 72.62767372010727
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "783039ec422985de1c08ce47c9c67df1201fc169",
         "name" : "Vishal Supermarket",
         "opening_hours" : {
            "open_now" : false
         },
         "photos" : [
            {
               "height" : 3120,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/105274333974561540604/photos\"\u003eAshutosh N. Mistry\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAAnsQzvM6iSa_O-MzIsCTvkI1XSCkSlm3XXn8FmB-xPtSGXmpA9Yizm4W7pi3EPkkQ7Ye6b0RgfcbaijPyBkqIQW2chxOHjubOoAn6L0Y5A0LVbfGjgjC8nSNWR1K8BhrxEhD_-QZybeY1_2ZtAoam5wdDGhStqaETDLSrXpBiaxQxfRB6Q_8JYQ",
               "width" : 4160
            }
         ],
         "place_id" : "ChIJ8WPWMxcqXDkRXQYi0tQeF6I",
         "plus_code" : {
            "compound_code" : "5JHH+GH Gandhinagar, Gujarat, India",
            "global_code" : "7JMJ5JHH+GH"
         },
         "rating" : 4,
         "reference" : "ChIJ8WPWMxcqXDkRXQYi0tQeF6I",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 73
      },
      {
         "formatted_address" : "FF-5,6,7, City Square, Godrej Garden City, Chandkheda, Ahmedabad, Gujarat, India",
         "geometry" : {
            "location" : {
               "lat" : 23.1136362,
               "lng" : 72.5558041
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.11502212989272,
                  "lng" : 72.55702612989272
               },
               "southwest" : {
                  "lat" : 23.11232247010728,
                  "lng" : 72.55432647010727
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "5cb27a4f0c7e2819d13eec270e1b11517b2effaa",
         "name" : "Hind Super Market",
         "opening_hours" : {
            "open_now" : false
         },
         "photos" : [
            {
               "height" : 4608,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/108583792813464292678/photos\"\u003eA Google User\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAABZiIF3RaOx0YPbq86N2lrIVExSe8me4nNKFZTjOqKYquecJPuUUFGe52BAW3KKHUT7riBOAiKhFnusFUfYN-9gGBBTpLC2QBX6DPJJULvUSUqUvHdiQI0IuRt8K-TWw4EhC9xHfqyxUCWNbPduBX-2NvGhQrGz84GmSAihjYnEv5FAZX9br7fw",
               "width" : 3456
            }
         ],
         "place_id" : "ChIJ8Td-s-SCXjkRmjJiVhyWoUY",
         "plus_code" : {
            "compound_code" : "4H74+F8 Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ4H74+F8"
         },
         "rating" : 4,
         "reference" : "ChIJ8Td-s-SCXjkRmjJiVhyWoUY",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 402
      },
      {
         "formatted_address" : "17, Sarkhej-Bavla Rd, Changodar, Gujarat 382213, India",
         "geometry" : {
            "location" : {
               "lat" : 22.9116812,
               "lng" : 72.42686239999999
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 22.91265157989272,
                  "lng" : 72.42823772989271
               },
               "southwest" : {
                  "lat" : 22.90995192010728,
                  "lng" : 72.42553807010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "c811b394d2b132cce754e22d7d489f29b36aeb1a",
         "name" : "Bigbasket Ahmedabad DC Hub",
         "opening_hours" : {
            "open_now" : true
         },
         "photos" : [
            {
               "height" : 4160,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/111180688046265022950/photos\"\u003eA Google User\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAAZl-fMGWgiyLqsMnZpMpbESTwd3JcKktEpmqb8CEhTj8M8Qs3x3VOrWevv_OI-GlO8SqLxbih8hGDzzu6moDnHUroBowtUrDeJs9OZR3eRIuMmApY83-Dl4lA3WUX6N0BEhD6XPyXVkwRAQoJXgkQSqZDGhQ0Bu9quffFDgNQik84QFgrBustgA",
               "width" : 3120
            }
         ],
         "place_id" : "ChIJwRaJxNSQXjkRYjT_i4UhtXQ",
         "plus_code" : {
            "compound_code" : "WC6G+MP Changodar, Gujarat, India",
            "global_code" : "7JJJWC6G+MP"
         },
         "rating" : 3.9,
         "reference" : "ChIJwRaJxNSQXjkRYjT_i4UhtXQ",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 127
      },
      {
         "formatted_address" : "Ground Floor, Shakun Square, Taltej - Shilaj Road, Near Heritage Homes, Thaltej, Ahmedabad, Gujarat 380058, India",
         "geometry" : {
            "location" : {
               "lat" : 23.0502576,
               "lng" : 72.5045319
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.05149477989272,
                  "lng" : 72.50586367989271
               },
               "southwest" : {
                  "lat" : 23.04879512010728,
                  "lng" : 72.50316402010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "0bbd9d2b03f0af25abc66202907db24e2f89c39b",
         "name" : "Hind Supermarket",
         "opening_hours" : {
            "open_now" : false
         },
         "photos" : [
            {
               "height" : 1908,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/108027611923773679200/photos\"\u003eOxfordDigital\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAAyisvQWIp71Bo39uAMvEOG9fU6nwWBmww5AYMRENf0a_n2-_AW0vZymjEjrl7ITUDf9faQnEeA__KRLtAlkfu5KXTu3_Dy6-L6yLX0P-7Xz4FBOfkPxbebfrXnqNUGnSYEhDmHX8yGZCsmmdQ-6KZhSLXGhTb-sD08Fioa5X5avsynJ3XjCZVCg",
               "width" : 4128
            }
         ],
         "place_id" : "ChIJHXevblmbXjkR89AkD5OhUk0",
         "plus_code" : {
            "compound_code" : "3G23+4R Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ3G23+4R"
         },
         "rating" : 4,
         "reference" : "ChIJHXevblmbXjkR89AkD5OhUk0",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 445
      },
      {
         "formatted_address" : "Plot No. 1560, Apna BAzar Building, Basement, GH Rd, Sector 6/A, Sector 6, Gandhinagar, Gujarat 382006, India",
         "geometry" : {
            "location" : {
               "lat" : 23.2101209,
               "lng" : 72.63647449999999
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.21143507989272,
                  "lng" : 72.63789252989272
               },
               "southwest" : {
                  "lat" : 23.20873542010728,
                  "lng" : 72.63519287010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "96bd587e1b8d4c930255ddcbfa6174c084fce0c1",
         "name" : "Vishal Super Market",
         "opening_hours" : {
            "open_now" : false
         },
         "photos" : [
            {
               "height" : 3096,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/101732403584384950313/photos\"\u003eChauhan Mukesh\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAAEy7oLJekexucKA_JPPHMDKPjn3nN82o66oODFZ59OlE8vhM2YT4bdkQY-zvdTsfo1eiWQNNUH-cKAnappd8UpU9_ocn3AWz7FL7hEfWEnNYLwzHhfpq_fsICKVH9c_e5EhDFws9abzfyyJ2u_z8-NUmSGhSDAmQtVWCUjrcw7W3g91E-9PCIIQ",
               "width" : 4128
            }
         ],
         "place_id" : "ChIJtawS5corXDkRSJk-5XIeT7k",
         "plus_code" : {
            "compound_code" : "6J6P+2H Sector 6, Gandhinagar, Gujarat, India",
            "global_code" : "7JMJ6J6P+2H"
         },
         "rating" : 3.9,
         "reference" : "ChIJtawS5corXDkRSJk-5XIeT7k",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 296
      },
      {
         "formatted_address" : "SV Desai Marg, Gulbai Tekra, Ahmedabad, Gujarat 380009, India",
         "geometry" : {
            "location" : {
               "lat" : 23.0300245,
               "lng" : 72.55491019999999
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.03137982989272,
                  "lng" : 72.55634427989271
               },
               "southwest" : {
                  "lat" : 23.02868017010728,
                  "lng" : 72.55364462010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "2c55c3efb28095cb765a1fbcb52f97a1c50c59cb",
         "name" : "Reliance Fresh",
         "opening_hours" : {
            "open_now" : false
         },
         "photos" : [
            {
               "height" : 1003,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/116586057895122037258/photos\"\u003eA Google User\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAA9s0-w_zvtHBb4stcL5QIkP1IOzMON7eJA9bJP5tcmBGjIasnq94gpDws6B_TsheBwlACrGEpj5_-cDtk3N92L2OrGkMBmJ11Pj3TVwiG1kCXUiWXaLnY7uXbdGotfkEKEhCr_n-VqlR8q_qE-EBIIzqKGhQBpF3Tco4ETESAFl0Hbpnejonf2A",
               "width" : 720
            }
         ],
         "place_id" : "ChIJgxwQOu6EXjkR2s_g92FcG7g",
         "plus_code" : {
            "compound_code" : "2HJ3+2X Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ2HJ3+2X"
         },
         "rating" : 3.9,
         "reference" : "ChIJgxwQOu6EXjkR2s_g92FcG7g",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 1105
      },
      {
         "formatted_address" : "B/8/2, Cellar, Opp: Petrol Pump, Memnagar, Ahmedabad, Gujarat 380052, India",
         "geometry" : {
            "location" : {
               "lat" : 23.049957,
               "lng" : 72.53756799999999
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.05137042989272,
                  "lng" : 72.5389246298927
               },
               "southwest" : {
                  "lat" : 23.04867077010728,
                  "lng" : 72.53622497010727
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "84fe4fd22cb1e03c4a7cc3485b0f91021533f4e8",
         "name" : "Kalapy Super Market",
         "photos" : [
            {
               "height" : 4160,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/110924547448001442809/photos\"\u003eVipul Gehlot\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAAIuka-Wayps2r2MVlEOPLli0NU1XRTzZnJ9QoOo8IYdRHWalbiT2nj3ywqncpXc6EPN3ADdcvCYGpSTdmoUzKY2myhk9lbWzgxbG37B23TQ3zHseyuMLWmMzQi_eKw-q5EhDZQvIarCyEN8g1jLF0AXHJGhRRkoCpzFYKhJYkrog-jMVb-horZA",
               "width" : 3120
            }
         ],
         "place_id" : "ChIJfZrrh6SEXjkRdJQPLXsVu_I",
         "plus_code" : {
            "compound_code" : "2GXQ+X2 Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ2GXQ+X2"
         },
         "rating" : 5,
         "reference" : "ChIJfZrrh6SEXjkRdJQPLXsVu_I",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 6
      },
      {
         "formatted_address" : "Shop No. 2, Shrinath Heights, Opp. Wrajvihar Society, Kathwada Rd, Naroda, Ahmedabad, Gujarat 382330, India",
         "geometry" : {
            "location" : {
               "lat" : 23.0657009,
               "lng" : 72.6621176
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.06705202989272,
                  "lng" : 72.66350082989271
               },
               "southwest" : {
                  "lat" : 23.06435237010728,
                  "lng" : 72.66080117010726
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "9e7158284a794f4601be32fda79d4b53748a9f45",
         "name" : "M-Mart Khodiyar Super Market",
         "opening_hours" : {
            "open_now" : false
         },
         "place_id" : "ChIJAQAAACyHXjkRaIALzekmQ9g",
         "plus_code" : {
            "compound_code" : "3M86+7R Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ3M86+7R"
         },
         "rating" : 3.5,
         "reference" : "ChIJAQAAACyHXjkRaIALzekmQ9g",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 6
      },
      {
         "formatted_address" : "65 Shrimali Society, Navrangpura Reliance Super , Opp-Navarang[pura Police Station Opp-naptune house, High Street, Gujarat 380009, India",
         "geometry" : {
            "location" : {
               "lat" : 23.0338884,
               "lng" : 72.567241
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.03514257989272,
                  "lng" : 72.56850627989272
               },
               "southwest" : {
                  "lat" : 23.03244292010728,
                  "lng" : 72.56580662010727
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "4e11d69c4bfdc2398194054c20d19f403746ba9b",
         "name" : "Reliance Fresh",
         "opening_hours" : {
            "open_now" : true
         },
         "photos" : [
            {
               "height" : 4608,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/109673950678556000955/photos\"\u003eNarendra Kumar\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAA5SCJnXXGoZm2vy4VpJao87radc6Te_XFqowTWIB4VJdtXsCBhNEHse_67WVGZkgpV2o2y7yCg4aW95xTPJXbidjOoLDcEV7GMA913eXeGP4n9cgb5PPnOjqFDI-FTu28EhAth_mSA5W38tHIdm7fKdncGhT0bX0okH4KZTualjdBfmsPQmqZog",
               "width" : 3456
            }
         ],
         "place_id" : "ChIJ6XaN-_WEXjkRFaUqP2FoI2g",
         "plus_code" : {
            "compound_code" : "2HM8+HV Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ2HM8+HV"
         },
         "rating" : 4.2,
         "reference" : "ChIJ6XaN-_WEXjkRFaUqP2FoI2g",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 268
      },
      {
         "formatted_address" : "Deora Avenue, Swapna Complex, Near LG Showroom Mithakhali Six Rd, Rashmi Society, Navrangpura, Ahmedabad, Gujarat 380009, India",
         "geometry" : {
            "location" : {
               "lat" : 23.0315435,
               "lng" : 72.5647888
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.03294682989272,
                  "lng" : 72.56607237989272
               },
               "southwest" : {
                  "lat" : 23.03024717010727,
                  "lng" : 72.56337272010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "924a2ad2994deed271ae12bd4de7ef8d8a13717e",
         "name" : "K Supermart",
         "opening_hours" : {
            "open_now" : true
         },
         "photos" : [
            {
               "height" : 3456,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/111066023176759892093/photos\"\u003eKaushal Shah\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAAAi9KZ4ETRuUlNtEIJ5eIk6sol1_T5VATfmXCMAesUqL8BDaSkrTwMYQpK1NCu0y9sURAFBZO1MviWOduBQQtcAjv_jLf5UL8LLDQspagIj1EYJRPDrhtCjcY5_4Zsbb5EhCNEfdeZ0BmY5iv2vxLvYMOGhSlIjKRvRdNfoKyc2fazpsPesQRhg",
               "width" : 4608
            }
         ],
         "place_id" : "ChIJzQmxivaEXjkRMaj5wBjtfso",
         "plus_code" : {
            "compound_code" : "2HJ7+JW Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ2HJ7+JW"
         },
         "rating" : 5,
         "reference" : "ChIJzQmxivaEXjkRMaj5wBjtfso",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 2
      },
      {
         "formatted_address" : "A-1, Shalvik Avenue, Opp. Uco Bank, Nr. Police Choki, Naranpura, Ahmedabad, Gujarat 380013, India",
         "geometry" : {
            "location" : {
               "lat" : 23.05259,
               "lng" : 72.55864
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.05381362989272,
                  "lng" : 72.55997897989272
               },
               "southwest" : {
                  "lat" : 23.05111397010727,
                  "lng" : 72.55727932010727
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "2acf9edde21dc83203132533c4f4b21cabc3cc0c",
         "name" : "Reliance Fresh",
         "opening_hours" : {
            "open_now" : false
         },
         "photos" : [
            {
               "height" : 3264,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/117433704581896392368/photos\"\u003ekuldeep thakur\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAAc7GTZtI2rj0x_BICLAAnd-BrsFePF1pyflxose8pHWwX1Z0gdBzRDLVaVMfxiaxM_mg_XJxyHS9Z_0u15CusmhmUgT5Wb2cVfineXG1pV8pbrGUTC6q9wDmmxQdpBowCEhA8mIwYfGe_6J29cscE5J3HGhTUYuoq_mbdKfhkGsOHfwn4KU0m8Q",
               "width" : 2448
            }
         ],
         "place_id" : "ChIJJ3Tx_4SEXjkRJtRyCNFG444",
         "plus_code" : {
            "compound_code" : "3H35+2F Naranpura, Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ3H35+2F"
         },
         "rating" : 3.9,
         "reference" : "ChIJJ3Tx_4SEXjkRJtRyCNFG444",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 567
      },
      {
         "formatted_address" : "Block-L, Swaminarayan Mandir Rd, Vastral, Ahmedabad, Gujarat 382418, India",
         "geometry" : {
            "location" : {
               "lat" : 22.9953539,
               "lng" : 72.6521554
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 22.99666757989272,
                  "lng" : 72.65358097989272
               },
               "southwest" : {
                  "lat" : 22.99396792010727,
                  "lng" : 72.65088132010727
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "e6da18d394108de251c0dce3cf216c294089aa88",
         "name" : "shree laxmi super market",
         "opening_hours" : {
            "open_now" : true
         },
         "place_id" : "ChIJgawVwDCGXjkRmMhB-1NimYk",
         "plus_code" : {
            "compound_code" : "XMW2+4V Vastral, Ahmedabad, Gujarat, India",
            "global_code" : "7JJJXMW2+4V"
         },
         "rating" : 4.2,
         "reference" : "ChIJgawVwDCGXjkRmMhB-1NimYk",
         "types" : [
            "grocery_or_supermarket",
            "supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 5
      },
      {
         "formatted_address" : "C Block, Akshar Apartments, Nehru Park, Vibhag-1, Mahavir Nagar Society, Vastrapur, Ahmedabad, Gujarat 380015, India",
         "geometry" : {
            "location" : {
               "lat" : 23.0363457,
               "lng" : 72.52779339999999
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.03761117989272,
                  "lng" : 72.52913857989272
               },
               "southwest" : {
                  "lat" : 23.03491152010728,
                  "lng" : 72.52643892010727
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "10498cc188883609a0e6255f5037862abf062c72",
         "name" : "Evergreen Supermart",
         "opening_hours" : {
            "open_now" : true
         },
         "photos" : [
            {
               "height" : 3456,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/102768425776872913404/photos\"\u003eHitesh Brahmbhatt\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAADh2sb9nd-9IMqAUhFsXQJS8r7R1xoubcB7XZssV1k6aU0VMQkvZIc-7hW4E8QJwFNWwddXqqGr4bn3g7nIU4gl8yMEpYHCiq-hWe2TyhXmEEpmTVMtBVd_ehZ1_G9PiIEhDRxqquiw_71ebBPSKKY-ouGhQIxeFEmCquLIVzvZ9RYrJJ7dAg1A",
               "width" : 4608
            }
         ],
         "place_id" : "ChIJVVVxabaEXjkRfOezy0-jSOg",
         "plus_code" : {
            "compound_code" : "2GPH+G4 Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ2GPH+G4"
         },
         "rating" : 3.8,
         "reference" : "ChIJVVVxabaEXjkRfOezy0-jSOg",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 44
      },
      {
         "formatted_address" : "26, Satymev Royal App, Sargashana Uvarshad, Gandhinagar, Gujarat 382421, India",
         "geometry" : {
            "location" : {
               "lat" : 23.216924,
               "lng" : 72.642324
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.21827382989272,
                  "lng" : 72.64367382989272
               },
               "southwest" : {
                  "lat" : 23.21557417010728,
                  "lng" : 72.64097417010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "32f00e160991d4d36375c813480a9445bf6da957",
         "name" : "Shree Bala Supermarket",
         "opening_hours" : {
            "open_now" : false
         },
         "photos" : [
            {
               "height" : 1152,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/102927117609888029675/photos\"\u003eShree Bala Super Market\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAAehAeYyht9QoHd_lglNjyp8bGN71_GA75Z6Opnr_Gr5ERw2qO8aJmsOmXiTuvlLVfxdla88LXxOcM2uQ0oE7of0LJdlOu7rlru1sAMoTtfSzhrT0scd4TqLe-beHrv9LLEhAS26QGsb-dq7MkNLv53lTcGhTUAUiaCbY6OLkyF8updAOnd9cc6g",
               "width" : 1920
            }
         ],
         "place_id" : "ChIJmQgO-7YrXDkRcMrzuZKA2Ls",
         "plus_code" : {
            "compound_code" : "6J8R+QW Gandhinagar, Gujarat, India",
            "global_code" : "7JMJ6J8R+QW"
         },
         "rating" : 3.8,
         "reference" : "ChIJmQgO-7YrXDkRcMrzuZKA2Ls",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 4
      },
      {
         "formatted_address" : "Aakash Metrocity, Varahi Nagar, Isanpur, Ahmedabad, Gujarat 382405, India",
         "geometry" : {
            "location" : {
               "lat" : 22.964636,
               "lng" : 72.60699509999999
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 22.96600682989272,
                  "lng" : 72.60831852989273
               },
               "southwest" : {
                  "lat" : 22.96330717010728,
                  "lng" : 72.60561887010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "7185d141206e070b748cd3930b17a652bb285ea0",
         "name" : "Shri Chetrapal Supermarket",
         "place_id" : "ChIJ_wDiuVGPXjkR3grLNHJ1rRw",
         "plus_code" : {
            "compound_code" : "XJ74+VQ Ahmedabad, Gujarat, India",
            "global_code" : "7JJJXJ74+VQ"
         },
         "rating" : 0,
         "reference" : "ChIJ_wDiuVGPXjkR3grLNHJ1rRw",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 0
      },
      {
         "formatted_address" : "Ellicon Tower, Vishala Circule, Sarkhej Road, Ahmedabad, Gujarat 380055, India",
         "geometry" : {
            "location" : {
               "lat" : 22.9950453,
               "lng" : 72.5329483
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 22.99638722989272,
                  "lng" : 72.53422467989273
               },
               "southwest" : {
                  "lat" : 22.99368757010727,
                  "lng" : 72.53152502010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "84ef87244fe2f46709810600145c3d2a9cc503a8",
         "name" : "Hearty Mart Super Market",
         "opening_hours" : {
            "open_now" : false
         },
         "photos" : [
            {
               "height" : 2848,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/107916127772526588305/photos\"\u003eHearty Mart Super Market\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAAtlm62rx5eKja2kClUTR9oUzrXOJknHUrYjgwuaQkzPYTuUKO3tB9ghU7_W0IT3PngE8B2zo8AcB8wA2zBvJ5ztGFXkdPhfCR0zt1xmA2WNCgGwXP00dIIHaDRH5wywovEhAXrbJeZK7nNo8IavIEVFGiGhSaDQoJXfAnJoKXP9s-93YF7omzIA",
               "width" : 4288
            }
         ],
         "place_id" : "ChIJ_YzkGTqFXjkRtxA7r9CpL2Y",
         "plus_code" : {
            "compound_code" : "XGWM+25 Ahmedabad, Gujarat, India",
            "global_code" : "7JJJXGWM+25"
         },
         "rating" : 4.4,
         "reference" : "ChIJ_YzkGTqFXjkRtxA7r9CpL2Y",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 203
      },
      {
         "formatted_address" : "Near Shell Petrol Pump, 100 Feet Ring Rd, Prahlad Nagar, 380054, Prahlad Nagar, Ahmedabad, Gujarat 380015, India",
         "geometry" : {
            "location" : {
               "lat" : 23.0116209,
               "lng" : 72.5116962
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.01297477989272,
                  "lng" : 72.51304497989273
               },
               "southwest" : {
                  "lat" : 23.01027512010728,
                  "lng" : 72.51034532010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "ca5d492525de8f187cc88865083748899b8b31ad",
         "name" : "Family SuperMarket",
         "opening_hours" : {
            "open_now" : false
         },
         "place_id" : "ChIJzxgisymbXjkRXnk8inQtyJE",
         "plus_code" : {
            "compound_code" : "2G66+JM Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ2G66+JM"
         },
         "rating" : 5,
         "reference" : "ChIJzxgisymbXjkRXnk8inQtyJE",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 1
      },
      {
         "formatted_address" : "Shop No 11-12, District Shopping Centre, Sector 21, Gandhinagar, Gujarat 382021, India",
         "geometry" : {
            "location" : {
               "lat" : 23.229305,
               "lng" : 72.6670768
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.23058392989272,
                  "lng" : 72.66838252989272
               },
               "southwest" : {
                  "lat" : 23.22788427010728,
                  "lng" : 72.66568287010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "8b91a11ca663953082c0a0ecfca13185ef215e55",
         "name" : "Reliance Fresh",
         "opening_hours" : {
            "open_now" : true
         },
         "photos" : [
            {
               "height" : 3120,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/105274333974561540604/photos\"\u003eAshutosh N. Mistry\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAA1qqjrEZ_CsX5rxJ3Ia0NZ2mxfvLmG8TZeWyurUFouSAofvsDUxKb06AFf287niqWFxEFtDBLssdxp33GXu1m0owbplLlaHAlMzP9yvobmyMW3-FihmuJ9VkIrvogsKjOEhDRESbcIsncXsDuEKk93TqVGhSvfzj2mRP6A8EHiIdkQOxOwgM4Aw",
               "width" : 4160
            }
         ],
         "place_id" : "ChIJxUqhvskrXDkRoZSYixZ9-x4",
         "plus_code" : {
            "compound_code" : "6MH8+PR Sector 21, Gandhinagar, Gujarat, India",
            "global_code" : "7JMJ6MH8+PR"
         },
         "rating" : 3.8,
         "reference" : "ChIJxUqhvskrXDkRoZSYixZ9-x4",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 401
      },
      {
         "formatted_address" : "32/ B, Near Sukhramdas Darbar, Sardarnagar, Hansol, Ahmedabad, Gujarat 382475, India",
         "geometry" : {
            "location" : {
               "lat" : 23.0803369,
               "lng" : 72.61873299999999
            },
            "viewport" : {
               "northeast" : {
                  "lat" : 23.08169892989272,
                  "lng" : 72.62007672989273
               },
               "southwest" : {
                  "lat" : 23.07899927010728,
                  "lng" : 72.61737707010728
               }
            }
         },
         "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
         "id" : "6e005439c51767abfa86f01254b0a849d99b50d5",
         "name" : "Blue Mart Supermarket",
         "opening_hours" : {
            "open_now" : true
         },
         "photos" : [
            {
               "height" : 3024,
               "html_attributions" : [
                  "\u003ca href=\"https://maps.google.com/maps/contrib/101955583992739633653/photos\"\u003emona jethani\u003c/a\u003e"
               ],
               "photo_reference" : "CmRaAAAA3umFkr9ceZ2XJjJOXgxUI-humS-9MeKqiBgZ-sud6NyuRZ4XP7WwjQv2PPULI8_NT2PhPNVf4uE2C18PcEtbIA449zsSC3Od_d8isRjq2wCy73kKVUKfMFcGsBJqZ4KjEhAYfKJoBIVNoHrTskL52rMxGhSmhdoKkcR4_pcb3fE9iG_bf063Tg",
               "width" : 4032
            }
         ],
         "place_id" : "ChIJY3Xbz1mBXjkRfJobMy_sej4",
         "plus_code" : {
            "compound_code" : "3JJ9+4F Hansol, Ahmedabad, Gujarat, India",
            "global_code" : "7JMJ3JJ9+4F"
         },
         "rating" : 4.1,
         "reference" : "ChIJY3Xbz1mBXjkRfJobMy_sej4",
         "types" : [
            "supermarket",
            "grocery_or_supermarket",
            "food",
            "store",
            "point_of_interest",
            "establishment"
         ],
         "user_ratings_total" : 65
      }
   ],
   "status" : "OK"
	}';
echo $result;	
}
?>