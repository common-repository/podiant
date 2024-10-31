(()=>{var e=window.wp.blocks.registerBlockType,C=window.wp.blockEditor.URLInput,t=window.wp.i18n.__;window.wp.element,e("podiant/player",{title:t("Podcast Player","podiant"),icon:React.createElement("svg",{width:"19px",height:"24px",viewBox:"0 0 19 24",version:"1.1"},React.createElement("path",{d:"M11.4718724,23.9017794 C11.3244777,23.8483986 10.9271528,23.7032028 10.5853679,23.5793594 C7.96644119,22.6313167 5.93068496,21.7793594 5.02068269,21.2540925 L4.85619872,21.1601423 L4.832701,21.0149466 C4.79638636,20.8014235 4.69171474,20.2014235 4.60626852,19.7188612 C4.56568156,19.4839858 4.51227767,19.1807829 4.48877996,19.0462633 C4.46528225,18.9117438 4.39692527,18.5231317 4.33711292,18.1814947 C4.27730056,17.8419929 4.20467127,17.4234875 4.1747651,17.252669 C4.08931887,16.7466192 4.08291041,16.7188612 4.0401873,16.7188612 C3.98037494,16.7188612 3.59373079,16.5928826 3.37584292,16.5010676 C3.12804888,16.3985765 2.74781319,16.2 2.48933837,16.0377224 L2.29922053,15.9181495 L2.27358666,15.7153025 C2.25863357,15.6042705 2.21163815,15.2284698 2.16891504,14.8825623 C2.12619193,14.5366548 2.07278804,14.1096085 2.05142648,13.9323843 C1.87198942,12.5551601 1.83781093,12.2263345 1.86985326,12.2071174 C1.94675486,12.1644128 4.11922505,10.7103203 4.36274678,10.5373665 C4.51227767,10.430605 4.63617469,10.3345196 4.63617469,10.3217082 C4.63831085,10.2469751 3.9440603,8.82491103 3.59159463,8.18434164 C2.8097617,6.75800712 2.01724799,5.62846975 1.28027433,4.88967972 C1.00471026,4.61423488 0.442901356,4.10818505 0.12461418,3.84982206 L-0.0825929085,3.68327402 L-0.0825929085,3.36512456 L-0.084729064,3.04911032 L0.0156702467,3.13024911 C1.51952375,4.33879004 2.19027659,5.0455516 3.04901112,6.33096085 C3.5787777,7.1252669 4.0209619,7.90676157 4.59772389,9.07259786 C4.84551794,9.57010676 4.94591725,9.75587189 4.96087034,9.73451957 C4.97155111,9.71743772 5.01000191,9.64697509 5.04631656,9.57651246 C5.21080054,9.25622776 5.56967467,8.69039146 5.84523873,8.31459075 C6.19984055,7.83202847 6.81078104,7.19572954 7.24228446,6.86263345 C8.08392975,6.2113879 8.99820432,5.81637011 9.97229125,5.6797153 C10.1325029,5.65836299 10.3824331,5.63701068 10.5276917,5.63487544 C10.6708141,5.63274021 10.7883027,5.62419929 10.7883027,5.61779359 C10.7883027,5.59644128 10.380297,5.11814947 10.0769629,4.78078292 C9.56428554,4.21067616 8.65428328,3.32241993 8.02198124,2.77580071 C6.99235426,1.88327402 5.71920556,0.960854093 4.59345158,0.290391459 C4.36488294,0.155871886 4.16194816,0.0341637011 4.14485892,0.0234875445 C4.12349736,0.00854092527 4.27730056,0.00213523132 4.58917927,0.00213523132 L5.06554196,7.10542736e-15 L5.27274905,0.132384342 C6.86418493,1.14234875 8.2398691,2.23131673 9.53010705,3.50391459 C10.202996,4.16797153 10.9100635,4.95373665 11.3330223,5.5088968 C11.4611917,5.67758007 11.4547832,5.67330961 11.589361,5.69039146 C11.7453004,5.70960854 12.1533061,5.80782918 12.2451608,5.84839858 C12.4502317,5.93807829 13.7682397,7.47117438 14.6782419,8.6797153 C15.5348403,9.81779359 16.5260165,11.3316726 17.1220039,12.4163701 C17.3719341,12.8711744 17.632545,13.3644128 17.8290714,13.7615658 L18.0512315,14.2099644 L17.9786022,14.4448399 C17.6496343,15.4953737 17.0985062,16.8298932 16.5238803,17.9679715 C15.3447224,20.2975089 13.9882637,22.1338078 12.4609125,23.4683274 C12.0934937,23.7907473 11.8243381,24 11.7794788,24 C11.7559811,23.9978648 11.6192672,23.9551601 11.4718724,23.9017794 Z M12.0356895,23.201985 C12.5846525,22.7322386 13.0396295,22.2710331 13.5544158,21.6689037 C14.7591437,20.2553942 15.8912463,18.3593271 16.7969283,16.2347924 C17.136559,15.4362236 17.4975502,14.4433506 17.4569654,14.4177281 C17.4441492,14.4113224 17.3501633,14.3985112 17.2497693,14.3899703 C16.6153647,14.3408605 15.3700519,14.1401507 14.5861243,13.9565225 C14.1375554,13.8540324 13.5437355,13.6917564 13.5586878,13.6789451 C13.5629599,13.6746747 13.718891,13.698162 13.9047267,13.7344606 C14.5583557,13.8604381 15.2974264,13.9479817 16.1667957,14.001362 C16.9144106,14.0483366 17.4163806,14.0632831 17.4163806,14.0376605 C17.4163806,14.0034972 17.0276209,13.2540382 16.7435272,12.7437228 C15.5366632,10.5743487 13.9367674,8.35159438 12.3069669,6.5836399 L12.0420977,6.29538645 L11.8626701,6.25054702 C11.2154492,6.08827101 10.4165693,6.08186537 9.68390675,6.23346534 C9.4403979,6.28471039 9.0580463,6.40641741 8.80812932,6.51317794 C7.54359216,7.04911583 6.50761155,8.04412403 5.61474579,9.58147576 C5.56348077,9.6690194 5.56134472,9.68183066 5.5955214,9.66261376 C5.64465038,9.63485602 6.25555854,9.14589277 6.41148964,9.00710407 L6.52256385,8.90674917 L6.58450908,9.04340265 C6.61868576,9.11599982 6.88782712,9.68183066 7.18260098,10.2967714 L7.71874765,11.4134866 L7.87040667,11.9195315 C7.95584837,12.1971089 8.03060985,12.4298469 8.03701798,12.4383877 C8.04342611,12.4469286 8.23353389,12.3124103 8.45568231,12.1415934 C9.00037315,11.7209569 10.0406258,10.9095768 10.4742425,10.5658079 C10.6686223,10.4120727 10.9185393,10.2134981 11.0296135,10.1259545 L11.2325376,9.96581369 L11.7024669,10.151577 C11.9609281,10.2562023 12.2300694,10.3608277 12.3005588,10.3864502 C12.4201772,10.4291544 13.0182691,10.7280839 13.87055,11.1700725 C14.2336773,11.3579711 14.3233911,11.4092161 14.2849423,11.4092161 C14.2529017,11.4092161 12.913603,11.0248782 12.5141631,10.901036 C12.3261913,10.8433853 11.9801524,10.7174079 11.7473238,10.6213234 C11.5123591,10.5252389 11.3137072,10.4483713 11.305163,10.4483713 C11.2966188,10.4483713 11.1663202,10.546591 11.0167973,10.668298 C9.95091206,11.5202471 7.24027413,13.6106184 5.05510265,15.2632715 C4.65139062,15.5686066 4.40788177,15.765046 4.40788177,15.7885333 C4.40788177,15.8077502 4.43137824,15.9379981 4.46341888,16.0767868 C4.49332347,16.2177107 4.52536411,16.3799867 4.53604432,16.4376374 C4.56594892,16.6127247 4.87781112,18.1607525 4.89917155,18.2418905 C4.91839593,18.3102172 4.97820512,18.7692875 5.01665389,19.1066508 C5.02947014,19.230493 5.11918393,19.787783 5.18540124,20.1635801 C5.20462563,20.263935 5.21957792,20.3578843 5.21957792,20.3728308 C5.21957792,20.385642 5.22812209,20.3984533 5.23880231,20.3984533 C5.26657086,20.3984533 7.04589426,18.9742677 7.77428475,18.3700031 C9.16698446,17.2148541 10.0705304,16.4162853 10.8266895,15.6796376 C11.2603061,15.2547307 11.1812725,15.3614912 10.6216294,15.9550798 C9.63691381,16.9991978 7.74010807,18.7628819 5.68523519,20.5479181 C5.36482881,20.8254954 5.33278818,20.8596588 5.36482881,20.8852813 C5.49512741,20.9877715 6.52042781,21.4660587 7.30221936,21.7884755 C7.58417697,21.9059121 7.8469102,22.0148078 7.88322292,22.0318895 C8.16731657,22.1578669 10.0427619,22.8688921 11.0082531,23.2169315 C11.307299,23.323692 11.5871206,23.4240469 11.6277054,23.4411286 C11.6704263,23.4561576 11.7152832,23.4603455 11.7345075,23.4518046 C11.7515959,23.4411286 11.8883026,23.3300976 12.0356895,23.201985 Z M4.05116201,16.1626066 C4.01490585,16.0427472 3.93386268,15.5675903 3.94665897,15.5568885 C3.95732254,15.5483272 4.14073604,15.4092046 4.35827299,15.244398 C4.75069257,14.9511705 5.24974792,14.5701889 6.79596639,13.3865773 C7.23743843,13.0462622 7.6,12.7551751 7.6,12.7380523 C7.6,12.7230699 7.51895682,12.4469652 7.42085193,12.1259133 C7.22890757,11.4987918 7.28222544,11.6229319 6.51658071,10.0305142 L6.34383078,9.67093596 L5.91088961,9.99840896 C5.12605044,10.5891446 4.65045496,10.9208983 3.33456972,11.8048613 C2.81205451,12.1537378 2.38124605,12.4426845 2.37698062,12.4469652 C2.36631705,12.4555266 2.38337877,12.6096316 2.52627068,13.7589976 C2.63717187,14.6429606 2.66489717,14.8655567 2.68835703,15.0710299 C2.69475518,15.139521 2.70968418,15.1994507 2.71821505,15.2058717 C2.73527677,15.2144331 3.22793396,14.8441532 5.09405971,13.4208228 C5.92795133,12.78514 6.78103739,12.136615 6.87061142,12.0702643 C7.0369632,11.9504049 7.03056506,11.9568259 5.81918286,13.0098763 C5.61017677,13.1918058 5.23908434,13.5149981 4.99808753,13.7268924 C4.754958,13.9387867 4.16206319,14.4546101 3.68006957,14.8719777 C3.19807595,15.2893452 2.80352365,15.6360814 2.80352365,15.6425024 C2.79925822,15.6810286 3.50731965,16.0320454 3.77817447,16.1262207 C4.01917128,16.2096942 4.06822373,16.2161152 4.05116201,16.1626066 Z",id:"Fiast",fill:"#000000","fill-rule":"nonzero",transform:"translate(8.983251, 12.000000) scale(-1, 1) rotate(-180.000000) translate(-8.983251, -12.000000)"})),category:"embed",attributes:{url:{type:"string"}},example:{attributes:{url:"https://platform.podiant.co/"}},edit:function(e){var t=e.setAttributes,a=e.attributes;return React.createElement("div",null,React.createElement("p",null,React.createElement("label",null,"Podcast website address",React.createElement(C,{value:a.url,onChange:function(e){t({url:e})},placeholder:"https://platform.podiant.co/"})),React.createElement("br",null)))},save:function(e){if(e.attributes.url){var C=e.attributes.url.match(/^https:\/\/([a-z0-9]+)\.podiant\.co/);if(C&&C.length>1){var t="https://player.podiant.co/".concat(C[1],"/embed.js");return React.createElement("script",{src:t})}}return React.createElement("div",{style:{border:"1px solid #999",borderRadius:"4px",background:"#fff",padding:"14px",color:"#999",fontFamily:"monospace"}},"Invalid website address. The URL must be something like this: ",React.createElement("code",null,"https://platform.podiant.co/"),".")}})})();