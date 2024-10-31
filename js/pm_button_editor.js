'use strict';
(function ($) {
  var btn_col_indx = '';
  var $this_button = null;
  var $pmfb_button_editor = null;
  var temp_params,get_bg_color,get_hv_color;
  $.fn.button_editor = function() {
    if($("#pmfb_button_editor").length < 1) {
      $('<div/>', { id: 'pmfb_button_editor',class:'pmfb_button_editor',style:'display:none;'}).appendTo('body');
      var btns_gallery = "<ul id='pmfb_btn_tabs'>"+
        "<li class='pmfb_active' data-toggle='#pmfb_btn_details'> Details </li>"+
        "<li data-toggle='#pmfb_btn_design'> Design </li>"+
        "</ul><div id='pmfb_btn_details' class='pmfb_tab'>"+
        "<div id='pmfb_b_n_f'><span style='margin-bottom:10px;'>Normal Button&nbsp;<input type='radio' name='f_btn' class='f_btn' id='pmfb_nrml_btn' checked='checked' value='1'>Fluid Button&nbsp;<input type='radio' id='pmfb_fluid_btn' class='f_btn' name='f_btn' value='2'></span><br><br></div>"+         
        "<label for='pmfb_btn_txt_main'>Main Text: &nbsp;&nbsp;</label><input type='text' name='pmfb_btn_txt_main' id='pmfb_btn_txt_main' class='pmfb_btn_edit' style='margin-bottom:10px;'><br>" +
        "<label for='pmfb_btn_txt_sub' id='pmfb_sub_t'>Sub Text: &nbsp;&nbsp;&nbsp;&nbsp;</label><input type='text' name='pmfb_btn_txt_sub' id='pmfb_btn_txt_sub' class='pmfb_btn_edit' style='margin-bottom:10px;'><br>" +      
        "<label for='pmfb_btn_url'>Action URL: &nbsp;</label><input type='text' name='pmfb_btn_url' id='pmfb_btn_url' class='pmfb_btn_edit' style='margin-bottom:10px;' placeholder='http://somedomain.com'><br>" +
        "<div id='pmfb_btn_l_t'><label for='pmfb_btn_left_ico'>Left Icon: &nbsp;&nbsp;&nbsp;&nbsp;</label><input type='text' name='pmfb_btn_left_ico' id='pmfb_btn_left_ico' class='pmfb_btn_edit' style='margin-bottom:10px;' placeholder='Font Awesome icon class'><br>" +
        "<label for='pmfb_btn_right_ico'>Right Icon: &nbsp;&nbsp;</label><input type='text' name='pmfb_btn_right_ico' id='pmfb_btn_right_ico' class='pmfb_btn_edit' style='margin-bottom:10px;' placeholder='Font Awesome icon class'></div><br>" +
        "<a href='http://fortawesome.github.io/Font-Awesome/icons/' class='get_font_icon' target='_blank'>Get Font Awesome Icon</a> Ex: fa-home"+
        "</div>"+
        "<div id='pmfb_btn_design' class='pmfb_tab' style='display:none;'><div id='pmfb_btn_tbl'>"+
        "<div><h3>Customizable Buttons </h3><label id='pmfb_btn_b_clr'>"+
          "<input type='text' name='pmfb_btn_bg_clr' id='pmfb_btn_bg_clr' class='pmfb_bg_edit'  style=''>&nbsp;"+
        "</label>"+
        "<label id='pmfb_btn_hr_clr'>"+
          "<input type='text' name='pmfb_btn_bghover_clr' id='pmfb_btn_bghover_clr' class='pmfb_bg_edit' style=''>" +
         "</label></div>"+
        "<ul style='border-bottom:1px dashed #ddd;'>";
          for(var flat=1; flat<=5; flat++){
           btns_gallery += "<li><a href='#' id='pmfb_btn_flat_"+flat+"' class='pmfb_btn_flat_"+flat+" pmfb_btn' ><span class='pmfb_btn_txt'><span class='pmfb_btn_txt_main'>Title</span></span></a></li>";
          }
          btns_gallery += "</ul><ul style='border-bottom:1px dashed #ddd;'>";
          for(var ghost=1; ghost<=3; ghost++){
           btns_gallery += "<li><a href='#' id='pmfb_btn_ghost_"+ghost+"' class='pmfb_btn_ghost_"+ghost+" pmfb_btn' ><span class='pmfb_btn_txt'><span class='pmfb_btn_txt_main'>Title</span></span></a></li>";
          }
          btns_gallery += "</ul><h3>Default Buttons</h3><ul>";
          
          for(var fat=1; fat<=15; fat++){
           btns_gallery += "<li><a href='#' id='pmfb_btn_fat_"+fat+"' class='pmfb_btn_fat_"+fat+" pmfb_btn'><span class='pmfb_btn_txt'><span class='pmfb_btn_txt_main'>Title</span></span></a></li>";
          }
          btns_gallery += "</ul></div></div>";
      $(btns_gallery).appendTo('#pmfb_button_editor');
    
      $pmfb_button_editor = $("#pmfb_button_editor");
      
      $pmfb_button_editor.on('click','#pmfb_btn_tabs li',function() {
        var pmfb_show_tab = $(this).data('toggle');
        $(this).siblings().removeClass('pmfb_active');
        $(this).addClass('pmfb_active');
        $pmfb_button_editor.find('.pmfb_tab').hide();
        $(pmfb_show_tab).show();
      });  
      $pmfb_button_editor.on('focusout','.pmfb_btn_edit',function(){
        var pmfb_case = $(this).attr('id');
        switch (pmfb_case) {
          case 'pmfb_btn_url':
            var btn_url = $(this).val();
            var re  = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;
            if (re.test(btn_url)) {
                $this_button.attr('href',btn_url);  
                var lead_box_link = btn_url.match('https://my.leadpages.net/leadbox/');
                var lp_lead_box   = btn_url.match('https://lp.leadpages.net/leadbox/') 
                if(lead_box_link!=null||lp_lead_box!=null){
                  var split_link = btn_url.split("/");
                  var get_id = split_link[4];
                  var replace_id = get_id.replace('2%3A',':');
                  $this_button.attr('data-leadbox',replace_id);
                }    
            } else{
              alert("please provide valid url");
              return false;
            }
          break;
        }  
      });

      $pmfb_button_editor.on('click','.f_btn',function() {
        var f_btn_value  = $(this).val();
        if(f_btn_value==1){
          $this_button.css({'width':'auto','text-align':'center'});
          $this_button.find('.pmfb_btn_ico').show();
          $this_button.find('.pmfb_btn_txt_sub').show();
          $pmfb_button_editor.find('#pmfb_btn_txt_sub').show();
          $pmfb_button_editor.find('#pmfb_btn_l_t').show();
          $pmfb_button_editor.find('#pmfb_sub_t').show();
          button_fluid = '0';
          $pmfb_button_editor.find('#pmfb_nrml_btn').attr('checked','check');
          $pmfb_button_editor.find('#pmfb_fluid_btn').removeAttr('checked');
        } else {
          $pmfb_button_editor.find('#pmfb_sub_t').hide();
          $this_button.css({'width':'100%','text-align':'center'});
          $this_button.find('.pmfb_btn_ico').hide();
          $this_button.find('.pmfb_btn_txt_sub').hide();
          $pmfb_button_editor.find('#pmfb_btn_txt_sub').hide();
          $pmfb_button_editor.find('#pmfb_btn_l_t').hide();
          button_fluid = '1';
          $pmfb_button_editor.find('#pmfb_fluid_btn').attr('checked','check');
          $pmfb_button_editor.find('#pmfb_nrml_btn').removeAttr('checked');
        }
      });  

      $pmfb_button_editor.on('keyup','.pmfb_btn_edit',function(){
        var pmfb_case = $(this).attr('id');
        var position = $this_button.offset();
        var check_icon;
        switch (pmfb_case) {
          case 'pmfb_btn_txt_main':
            var btn_txt = $(this).val();
            $pmfb_button_editor.css({"top": position.top + parseInt( $this_button .css( "height" ).slice(0,-2)), "left" : position.left });
            $this_button.find("span.pmfb_btn_txt_main").text(btn_txt);
            break;
          case 'pmfb_btn_txt_sub':
            var btn_txt = $(this).val();
            $pmfb_button_editor.css({"top": position.top + parseInt( $this_button .css( "height" ).slice(0,-2)), "left" : position.left });
            $this_button.find("span.pmfb_btn_txt_sub").text(btn_txt);  
            break;
          case 'pmfb_btn_left_ico':
            var btn_txt = 'fa '+$(this).val().trim();
            $this_button.find("span.pmfb_btn_ico").first().children('i').removeClass().addClass(btn_txt);
            break;
          case 'pmfb_btn_right_ico':
            var btn_txt = 'fa '+$(this).val().trim();
            if(btn_txt!=""){
              check_icon = btn_txt;
            }
            $this_button.find("span.pmfb_btn_ico").last().children('i').removeClass().addClass(btn_txt);
            
            break;    
        }
      });
      var pmfb_bghover_clr='',pmfb_bg_clr='',get_bg_value='',get_hvr_value='';
      $('#pmfb_btn_bg_clr').each(function(){
        $(this).click(function(){ 
          var btn_class;
          var check_name = $this_button.attr('class');
          if(check_name.indexOf('pmfb_btn_flat_')>=0||check_name.indexOf('pmfb_btn_ghost_')>=0){
            btn_class = check_name;     
          }else{
            btn_class = "pmfb_btn_flat_1 pmfb_btn";
          }
          $('#plugmatter_apply_color_hover').hide();
          //$('#plugmatter_apply_color').toggle();  
          if($('#plugmatter_apply_color').css('display')=='none'||x==0){
            $('#plugmatter_apply_color').show();  
          }
          

          $('#plugmatter_apply_color').on('click',function(){
            get_bg_value = $('#pmfb_btn_bg_clr').val();
            var get_class = $this_button.attr('class');
            if(get_class.indexOf('pmfb_btn_flat_')>=0||get_class.indexOf('pmfb_btn_ghost_')>=0){
              btn_class=get_class;
            }else{
              $this_button.removeClass().addClass(btn_class);
            }
            var split_classes = btn_class.split(' ');
            apply_btn_color(split_classes[0],get_bg_value,get_hvr_value);
            $(this).hide();
            if($(this).parent().siblings('.pmfb_btn_b_c').hasClass('wp-picker-open')){
              $(this).parent().siblings('.pmfb_btn_b_c').trigger('click');  
            }
          });
        });
        $(this).wpColorPicker({
          color: false,
          mode: 'hsl',
          palettes:false,
          change: function(event,ui){
            var hexcolor = $(this).wpColorPicker('color');
            pmfb_bg_clr = hexcolor;
            $("a[id^='pmfb_btn_flat_']").css('background-color',pmfb_bg_clr);
            $("a[id^='pmfb_btn_ghost_']").css('border-color',pmfb_bg_clr);
            $("a[id^='pmfb_btn_ghost_']").css('color',pmfb_bg_clr);
            $('#plugmatter_apply_color').show();  
          }
        });
      });
      $('#pmfb_btn_bghover_clr').each(function(){
        $(this).click(function(){
          var btn_class;
          var check_name = $this_button.attr('class');
          if(check_name.indexOf('pmfb_btn_flat_')>=0||check_name.indexOf('pmfb_btn_ghost_')>=0){
            btn_class = check_name;     
          }else{
            btn_class = "pmfb_btn_flat_1 pmfb_btn";
          }
          $('#plugmatter_apply_color').hide();
          if($('#plugmatter_apply_color_hover').css('display')=='none'){
            $('#plugmatter_apply_color_hover').show();  
          }
          $('#plugmatter_apply_color_hover').on('click',function(){
            get_hvr_value = $('#pmfb_btn_bghover_clr').val();
            var get_class = $this_button.attr('class');
            if(get_class.indexOf('pmfb_btn_flat_')>=0||get_class.indexOf('pmfb_btn_ghost_')>=0){
              btn_class = get_class;
            }else{
              $this_button.removeClass().addClass(btn_class);
            }
            var split_classes = btn_class.split(' ');
            apply_btn_color(split_classes[0],get_bg_value,get_hvr_value);
            $(this).hide();
            if($(this).parent().siblings('.pmfb_btn_hr_c').hasClass('wp-picker-open')){
              $(this).parent().siblings('.pmfb_btn_hr_c').trigger('click');  
            }
          });
        });
        $(this).wpColorPicker({
          color: false,
          mode: 'hsl',
          palettes:false,
          change: function(event,ui){
            var hexcolor = $(this).wpColorPicker('color');
            pmfb_bghover_clr = hexcolor;
            $("a[id^='pmfb_btn_flat_']").hover(function(e) { 
              $(this).css("background-color",(e.type === "mouseenter")? ((pmfb_bghover_clr!='')?(pmfb_bghover_clr):(pmfb_bg_clr)) :(pmfb_bg_clr)); 
            });

            $("a[id^='pmfb_btn_ghost_']").hover(function(e) { 
              $(this).css({"background-color": pmfb_bghover_clr,"border-color":pmfb_bghover_clr,"color": "white"}); 
            },function(){
              $(this).css({"background-color": "transparent","border-color":pmfb_bg_clr,"color": pmfb_bg_clr});
            });
          }
        });
      });
      $pmfb_button_editor.on("click","a",function(e) { 
        e.preventDefault();
        e.stopPropagation();
          
        var btn_class;
        btn_class = $(this).attr("class");  

        apply_btn_color($(this).attr('id'),pmfb_bg_clr,pmfb_bghover_clr);       
        $this_button.removeClass().addClass(btn_class);
      }); 
  }
  function apply_btn_color(btn_id,bg_color,bg_hover){
    $('#pmfb_custom_btn_style').html(" ");
    var ghost_patt = /ghost/i;
    var flat_patt = /flat/i;
    var pm_button_style = '';
    if(flat_patt.test(btn_id)){
      if(bg_color!=''){
        pm_button_style =   '.'+btn_id+'{\n';
        pm_button_style += 'background-color:'+bg_color+' !important;\n';
        pm_button_style += 'color: #ffffff !important;';
        pm_button_style += "}\n"; 
      }
      if(bg_hover!=''){
        pm_button_style += '.'+btn_id+':hover {\n';
        pm_button_style += 'background-color:'+bg_hover+' !important;';
        pm_button_style += 'color: #ffffff !important;';
        pm_button_style += "}";   
      }
    }
    if(ghost_patt.test(btn_id)){
      pm_button_style = '.'+btn_id+'{\n'; 
      pm_button_style += 'border-color:'+bg_color+' !important;';
      pm_button_style += 'color:'+bg_color+' !important;';
      pm_button_style += 'background-color: transparent !important;';
      pm_button_style += "}"; 

      pm_button_style += '.'+btn_id+':hover {\n';
      pm_button_style += 'border-color:'+bg_hover+" !important;";
      pm_button_style += 'background-color:'+bg_hover+" !important;";
      pm_button_style += 'color: #ffffff !important;';
      pm_button_style += "}";     
    }
    pmfb_custom_style.appendChild(document.createTextNode(pm_button_style));
    document.getElementsByTagName('head')[0].appendChild(pmfb_custom_style);
  } 
  this.on('click',function(e) {
  
    $this_button = $(this);
    e.preventDefault();
    e.stopPropagation();
    $pmfb_button_editor.hide();
    $pmfb_button_editor.show();

    $('#pmfb_btn_bg_clr').wpColorPicker();
    $('#pmfb_btn_bghover_clr').wpColorPicker();
    $('#pmfb_btn_b_clr .wp-color-result').addClass('pmfb_btn_b_c');
    $('#pmfb_btn_b_clr .wp-picker-input-wrap').append('<input type="button" class="button button-small" id="plugmatter_apply_color" value="Apply" style="display:none;">');
    $('#pmfb_btn_hr_clr .wp-color-result').addClass('pmfb_btn_hr_c');
    $('#pmfb_btn_hr_clr .wp-picker-input-wrap').append('<input type="button" class="button button-small" id="plugmatter_apply_color_hover" value="Apply" style="display:none;">');
    $('.pmfb_btn_b_c').attr('title','Button-Background').css('height','auto');;
    $('.pmfb_btn_hr_c').attr('title','Button-Hover').css('height','auto');
    if(button_fluid=='0'){
      $this_button.css({'width':'auto','text-align':'center'});
      $this_button.find('.pmfb_btn_ico').show();
      $this_button.find('.pmfb_btn_txt_sub').show();
      $pmfb_button_editor.find('#pmfb_btn_txt_sub').show();
      $pmfb_button_editor.find('#pmfb_btn_l_t').show();
      $pmfb_button_editor.find('#pmfb_sub_t').show();
      button_fluid = '0';
      $pmfb_button_editor.find('#pmfb_nrml_btn').attr('checked','check');
      $pmfb_button_editor.find('#pmfb_fluid_btn').removeAttr('checked');
    }
    if(button_fluid=='1'){
      $this_button.css({'width':'100%','text-align':'center'});
      $pmfb_button_editor.find('#pmfb_sub_t').hide();
      $this_button.find('.pmfb_btn_ico').hide();
      $this_button.find('.pmfb_btn_txt_sub').hide();
      $pmfb_button_editor.find('#pmfb_btn_txt_sub').hide();
      $pmfb_button_editor.find('#pmfb_btn_l_t').hide();
      $pmfb_button_editor.find('#pmfb_fluid_btn').attr('checked','check');
      $pmfb_button_editor.find('#pmfb_nrml_btn').removeAttr('checked');
    }
    var position = $(this).offset();
    var get_main_text = $this_button.find(".pmfb_btn_txt_main").text();
    var get_sub_text  = $this_button.find(".pmfb_btn_txt_sub").text();
    var get_url       = $this_button.attr('href');
    var left_icon     = $this_button.find("span.pmfb_btn_ico").first().children('i').attr('class').slice(3);
    var right_icon    = $this_button.find("span.pmfb_btn_ico").last().children('i').attr('class').slice(3);
    $pmfb_button_editor.find('#pmfb_btn_txt_main').val(get_main_text);
    $pmfb_button_editor.find('#pmfb_btn_txt_sub').val(get_sub_text);
    $pmfb_button_editor.find('#pmfb_btn_url').val(get_url);
    $pmfb_button_editor.find('#pmfb_btn_left_ico').val(left_icon);
    $pmfb_button_editor.find('#pmfb_btn_right_ico').val(right_icon);
    $this_button.find("span.pmfb_btn_txt_sub").text();
    $pmfb_button_editor.css({"top": position.top + parseInt( $( this ).css( "height" ).slice(0,-2)), "left" : position.left });
    e.stopPropagation();
    $pmfb_button_editor.on( "click", function(e){ 
      e.stopPropagation();
    });
    $( document ).one("click", function() {
      $('#pmfb_button_editor').hide();
    });
    $( document ).on( 'keydown', function ( e ) {
      if ( e.keyCode === 27 ) {
        $('#pmfb_button_editor').hide();
      }
    });
  });
}
})(jQuery);