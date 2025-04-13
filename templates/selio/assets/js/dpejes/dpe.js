dpe = function(){
var GG_SCALE = [
	['A',0, "&le; 5"],
	['B', 5.5, "6 - 10"],
	['C', 10.5, "11 - 20"],
	['D', 20.5, "21 - 35"],
	['E', 35.5, "36 - 55"],
	['F', 55.5, "56 - 80"],
	['G', 80.5, "&gt; 80"]
];
var ENERGY_SCALE = [
	['A',0, "&le; 50"],
	['B', 50.5, "51 - 90"],
	['C', 90.5, "91 - 150"],
	['D', 150.5, "151 - 230"],
	['E', 230.5, "231 - 330"],
	['F', 330.5, "331 - 450"],
	['G', 450.5, "&gt; 450"]
];
var LETTERS_POSITION_LEFT = [47, 75, 101, 131, 157, 188, 215];
var LETTERS_POSITION_TOP = [26, 67, 104, 144, 182, 221, 259];
var NUMBERS_POSITION_LEFT = [11, 11, 11, 11, 11, 11, 11];
var NUMBERS_POSITION_TOP = [34, 72, 111, 150, 188, 227, 265];
var POINTER_POSITION_TOP = [34, 72, 111, 150, 188, 227, 265];
var STICKER_WIDTH = [69,98,127,155,185,213,243];
var STICKER_HEIGHT = 35;
var STICKER_LETTERS_POSITION_TOP = [3,5,4,4,4,5,4];
var STICKER_LETTERS_POSITION_LEFT = [37, 69, 97, 125, 153, 183, 209];
function get_img_folder(document_url){
	var method = document_url.slice(0,4);
	if(method == 'file'){
		var file_regex = new RegExp("^(file:///?([^\\\\/]+[/\\\\](?!dpejs))*)(([^\\\\/]+[/\\\\])dpejs[/\\\\])?[^\\\\/]*$");
		var m = file_regex.exec(document_url);
		var path = m[1] + ((m[4] === undefined)?'':m[4]) + 'dpejes';
		return path;
	}
	if(method == 'http'){
		var http_regex = new RegExp("^(http://[^/]+)(/.*)?$");
		var m = http_regex.exec(document_url);
		var path = m[1] + '/estatepointdev/templates/bootstrap2-responsive/assets/js/dpejes';
		return path;
	}
	throw("unhandled document URL: should begin with http:// or file://");
}
//var IMG_FOLDER = get_img_folder(document.URL);
var ENERGY_STICKERS = ["energie-a.png", "energie-b.png", "energie-c.png", "energie-d.png", "energie-e.png", "energie-f.png", "energie-g.png"];
var GG_BGCOLORS = ['#f6edfd', '#e1c2f8', '#d4a9f5', '#cb95f3', '#ba72ef', '#a74deb', '#8a19df'];
var GG_BACKGROUNDS = [
	['ges180.png', 0],
	['ges200.png', 181],
	['ges220.png', 201],
	['ges230.png', 221],
	['ges250.png', 231],
	['ges312.png', 251]
]
var ENERGY_BACKGROUND = "energie.png";
var HEIGHT = 312;
var DEFAULT_HEIGHT = 250;
var LEFT_FRAME_WIDTH = 264;
var POINTER = "pointer.png";
var POINTER_WIDTH = 76;
var LARGE_POINTER = "large_pointer.png"
var LARGE_POINTER_WIDTH = 80;
var POINTER_HEIGHT = 36;
var POINTER_DIV_HEIGHT = 46;
var TITLE_STYLE = {
	'position': 'absolute',
	'padding': '0',
	'margin': '0',
	'border-width': '0',
	'color': 'black',
	'font-family': 'arial, helvetica, sans, sans-serif',
	'font-weight': '900',
	'font-style': 'italic',
	'z-index': '1'
};
var TITLE3_STYLE = {
	'position': 'absolute',
	'padding': '0',
	'margin': '0',
	'border-width': '0',
	'color': 'black',
	'font-family': 'arial, helvetica, sans, sans-serif',
	'font-weight': '900',
	'z-index': '1',
	'text-align': 'center'
};
var NUMBER_STYLE = {
	'position': 'absolute',
	'padding': '0',
	'margin': '0',
	'border-width': '0',
	'color': 'black',
	'font-family': 'arial, helvetica, sans, sans-serif',
	'font-weight': '900',
	'z-index': '1'
};
var LETTER_STYLE = {
	'position': 'absolute',
	'padding': '0',
	'margin': '0',
	'border-width': '0',
	'color': 'black',
	'font-family': 'calibri, "DejaVu Sans", arial, helvetica, sans, sans-serif',
	'font-weight': '500',
	
	'z-index': '1'
};
var UNIT_STYLE = {
	'position': 'absolute',
	'padding': '0',
	'margin': '0',
	'border-width': '0',
	'color': 'black',
	'font-family': 'calibri, cambria, STIXGeneral, "Liberation Serif", "PT Sans", garamond, baskerville, helvetica, arial, sans-serif',
	'font-weight': '900',
	'text-align': 'center'
};
var LETTERS_REGEX = new RegExp("^[a-gA-G]$");

function jdiv(style, html_content){
	var result = jQuery(document.createElement("div"));
	if(style){
		result.css(style);
	}
	if(html_content)
		result.html(html_content);
	return result;
}

function jimg(src, style){
	var img = document.createElement("img");
	img.style.msInterpolationMode = 'bicubic';
	var result = jQuery(img);
	result.attr("src", (IMG_FOLDER == "")?src:(IMG_FOLDER.match(new RegExp("/$")))?IMG_FOLDER + src:IMG_FOLDER + "/" + src);
	result.css({'opacity': 0.99});	
	if(style){
		result.css(style);
	}
	return result;
}

function dict__add__(){
	var result = {}
	for(var i = 0; i < arguments.length; i++){
		for(var key in arguments[i]){
			result[key] = arguments[i][key];
		}
	}	
	return result;
}

function valid_type(type){
	if(typeof(type) == 'string'){
		var energy = new RegExp("^energ(ie|y)$", "gi");
		if(type.match(energy))
			return "energy";
		var gg = new RegExp("^(ges|gg|ghg)$", "gi");
		if(type.match(gg))
			return "gg";
		var energy_sticker = new RegExp("^energ(ie|y)[-_]?sticker$", "gi");
		if(type.match(energy_sticker)){
			return "energy-sticker";
		}
		var gg_sticker = new RegExp("^(ges|gg|ghg)[-_]?sticker$", "gi");
		if(type.match(gg_sticker)){
			return "gg-sticker";
		}
	}
	throw('Please define a valid dpe attribute in your dpe div.\nEx: <div dpe="energy" val="180" ht="250">Text that will show up when Javascript is not available</div>\nAllowed attributes:\n"energy" -> kWh consumptions\n"gg" -> greenhouse gaz emissions\n"energy-sticker" -> small sticker for energy consumption\ngg-sticker->small sticker for greenhouse gaz emissions');
}

function valid_show_unit(show_unit){
	if(show_unit == undefined)
		return undefined;
	if(typeof(show_unit) == 'string'){
		var tr = new RegExp("^(true|yes|1|vrai|oui)$", "gi");
		if(show_unit.match(tr))
			return true;
		var fls = new RegExp("^(false|no|0|faux|non)$", "gi");
		if(show_unit.match(fls))
			return false;
	}
	if(typeof(show_unit) == "boolean"){
		return show_unit;
	}
	throw('Unexpected value for attribute "show_unit". Please use one of the following: "true", "vrai", "false", "faux".');
}

function scale_float_to_letter(scale, flt){
	var current_letter = 'A'; // if a value is negative we return 'A'
	for(i=0;i<scale.length;i++){
		if(flt < scale[i][1]){
			break;
		}
		current_letter = scale[i][0];
	}
	return current_letter;
}

function gg_background(height){
		return scale_float_to_letter(GG_BACKGROUNDS, height);
}	
		
function letter_to_index(letter){
	return parseInt(letter, 17) - 10;
}

function type_to_scale(type){
	if(type == 'energy' || type == 'energy-sticker')
		return ENERGY_SCALE;
	if(type == 'gg' || type == 'gg-sticker')
		return GG_SCALE;
	throw("Unknown type (not in 'energy', 'gg', 'energy-sticker', 'gg-sticker')");
}

/* Class Resizer */

function Resizer(size_factor){
	var obj = new Object();
	Resizer__init__(obj, size_factor);
	return obj;
}

function Resizer__init__(self, size_factor){
	self.size_factor = size_factor;
}

function Resizer_resize(self, value){
	result = self.size_factor * value;
	return result;
}

function Resizer_px(self, value){
	return Math.round(Resizer_resize(self, value)) + "px";
}

function Resizer_letter(self, value){
	return (Math.round(Resizer_resize(self, value)*100)/100) + "px";
}

/* endclass Resizer */

/* class DpeValue */

function DpeValue(scale, str){
	var obj = new Object();
	DpeValue__init__(obj, scale, str);
	return obj;
}

function DpeValue__init__(self, scale, strg){
	self.scale = scale;
	self.div_width = null;
	if(typeof(strg) == "string"){
		if(strg.match(LETTERS_REGEX)){
			self.letter = strg.toUpperCase();
			self.number = null;
		}
		else{
			self.number = parseFloat(strg);
			if(self.number == NaN){
				throw("This string should either represent a number or consist of a single letter from A to G");
			}
		}
	}
	else{
		if (typeof(strg) == "number"){
			self.number = strg;
		}
		else{
			throw("This value should be either a number either a single letter from A to G, received " + strg);
		}
	}
	if(self.number){
		self.number = Math.round(self.number);
		self.letter = scale_float_to_letter(self.scale, self.number);
	}
}

function DpeValue_create_sticker(self, resizer){
	
	var div = jdiv({
		'position': 'relative',
		'margin': '0',
		'padding': '0',
		'color': 'black'
	});
	var color = (self.letter == 'G')?'white':'black';
	/* background creation */
	if(self.scale == GG_SCALE){
		
		div.width( Resizer_resize(resizer, STICKER_WIDTH[letter_to_index(self.letter)]) - 2);
		div.height( Resizer_resize(resizer, STICKER_HEIGHT) - 2);
		div.css({
			'background-color': GG_BGCOLORS[letter_to_index(self.letter)],
			'border-style': 'solid',
			'border-color': 'black',
			'border-width': '1px'
		});
	}
	else{
		
		div.width(Resizer_resize(resizer,STICKER_WIDTH[letter_to_index(self.letter)]));
		div.height(Resizer_resize(resizer, STICKER_HEIGHT));
		var img = jimg(
			ENERGY_STICKERS[letter_to_index(self.letter)],
			{
				'position': 'absolute',
				'margin': '0',
				'padding': '0',
				'border-width': '0',
				'top': '0',
				'left': '0'
			}	
		);
		img.width(Resizer_resize(resizer, STICKER_WIDTH[letter_to_index(self.letter)]));
		img.height(Resizer_resize(resizer, STICKER_HEIGHT));
		
		div.append(img);
	}
	/* end of background creation */

	if(self.number){
		div.append(jdiv(
			dict__add__(
				NUMBER_STYLE,
				{
					'top': Resizer_px(resizer,10),
					'left': Resizer_px(resizer, 11),
					'font-size': Resizer_letter(resizer, 13),
					'letter-spacing': Resizer_px(resizer, -0.5),
					'color': color
				}
			),
			self.number + ""
		));
	}
	
	div.append(jdiv(
		dict__add__(
			LETTER_STYLE,
			{
				'top': Resizer_px(resizer, STICKER_LETTERS_POSITION_TOP[letter_to_index(self.letter)]),
				'left': Resizer_px(resizer, STICKER_LETTERS_POSITION_LEFT[letter_to_index(self.letter)]),
				'font-size': Resizer_letter(resizer, 22),
				'color': color
			}
		),
		self.letter
	));

	return div;
		
}

function DpeValue_create_pointer(self, resizer, unit){
	/* returns a div that shows the pointer and the value, to be used in the right side of a DpeCanvas */
	if(show_unit == undefined){
		var show_unit = self.number;
	}
	var pointer_url;
	if(show_unit){
		pointer_url = LARGE_POINTER;
		self.div_width = LARGE_POINTER_WIDTH;
		 
	}
	else{
		pointer_url = POINTER;
		self.div_width = POINTER_WIDTH;
	}
	var div = jdiv({
		'position': 'absolute',
		'border-width': '0',
		'margin': '0',
		'padding': '0',
		'top': Resizer_px(resizer, LETTERS_POSITION_TOP[letter_to_index(self.letter)]-((self.letter == 'A')?5:8)),
		'left': (Resizer_resize(resizer, LEFT_FRAME_WIDTH) + 1) + "px"
	});
	div.width(Resizer_resize(resizer,self.div_width));
	div.height(Resizer_resize(resizer,POINTER_HEIGHT + 19));
	var style_pointer_img = {
		'position': 'absolute',
		'border-width': '0',
		'margin': '0',
		'padding': '0',
		'top': '0',
		'left': '1px'
	}	
	var img = jimg(pointer_url,style_pointer_img);
	img.width(Resizer_resize(resizer,self.div_width)-1);
	img.height(Resizer_resize(resizer,POINTER_HEIGHT));

	var value_div = jdiv();
	value_div.css({
		'position': 'absolute',
		'border-width': '0',
		'margin': '0',
		'padding': '0',
		'left': Resizer_px(resizer, 21),
		'text-align': 'center',
		'vertical-align': 'center',
		'color': 'white',
		'display': 'inline'	
	})
	value_div.width(Resizer_resize(resizer, self.div_width - 21 - 5));
	
	if(self.number){
		value_div.css({
			'font-family': 'sans, sans serif',
			'font-size': Resizer_letter(resizer, 17),
			'font-weight': '900',
			'top': Resizer_px(resizer, 7)
		});
		value_div.html((dpe.show_numbers?self.number:"") + "");
	}
	else{
		value_div.css(
			dict__add__(
				LETTER_STYLE,
				{
					'color': 'white',
					'font-size': Resizer_letter(resizer, 22),
					'font-weight': '900',
					'top': Resizer_px(resizer, 5)
				}
			)
		);
			
		value_div.html(self.letter);
	}
	div.append(img);
	div.append(value_div);
	
	var unit_div = jdiv(dict__add__(
		UNIT_STYLE,
		{
			'top': Resizer_px(resizer, POINTER_HEIGHT + 4),
			'left': '0',
			'font-size': Math.floor(Resizer_resize(resizer, 12)) + "px"
		} 
	));
	unit_div.height(Resizer_resize(resizer, 15));
	unit_div.width(Resizer_resize(resizer, self.div_width));
	unit_div.html(unit);
	div.append(unit_div);
	return div;
}

function DpeValue_get_div_width(self){
	return self.div_width;
}

/* endclass DpeValue */

/* class LeftFrame */

function LeftFrame(scale, background_url, resizer, title1, title2){
	var obj = new Object();
	LeftFrame__init__(obj, scale, background_url, resizer, title1, title2);
	return obj;
}

function LeftFrame__init__(self, scale, background_url, resizer, title1, title2){
	self.scale = scale;
	self.background_url = background_url;
	self.resizer = resizer;
	if(title1 == undefined)
		self.title1 = (self.scale == GG_SCALE)?"Faible &eacute;mission de GES":"Logement &eacute;conome";
	else
		self.title1 = title1;
	if(title2 == undefined)
		self.title2 = (self.scale == GG_SCALE)?"Forte &eacute;mission de GES":"Logement &eacute;nergivore";
	else
		self.title2 = title2;
}

function LeftFrame_create(self){
	self.div = jdiv({
		'position': 'absolute',
		'border-style': 'solid',
		'border-width': '0',
		'border-right-width': '1px',
		'font-family': 'arial, sans, sans-serif',
		'top': '0',
		'left': '0'
	})
	self.div.height(Resizer_resize(self.resizer, HEIGHT));
	self.div.width(Resizer_resize(self.resizer, LEFT_FRAME_WIDTH));
	var img = jimg(
		self.background_url,
		{
			'position': 'absolute',
			'border-width': '0',
			'margin': '0',
			'padding': '0',
			'top': '0',
			'left': '0'
		}
	);
	img.height(Resizer_resize(self.resizer, HEIGHT));
	img.width(Resizer_resize(self.resizer, LEFT_FRAME_WIDTH));
	self.div.append(img);
	var color;
	for(var i = 0; i < self.scale.length; i++){
		color = (self.scale[i][0] == 'G')?'white':'black';
		self.div.append(jdiv(
			dict__add__(
				NUMBER_STYLE,
				{
					'top': Resizer_px(self.resizer,NUMBERS_POSITION_TOP[i]),
					'left': Resizer_px(self.resizer, NUMBERS_POSITION_LEFT[i]),
					'font-size': Resizer_letter(self.resizer, 13),
					'letter-spacing': Resizer_px(self.resizer, -0.5),
					'color': color
				}
			),
			(dpe.show_numbers?self.scale[i][2]:"")
		));
		self.div.append(jdiv(
			dict__add__(
				LETTER_STYLE,
				{
					'top': Resizer_px(self.resizer, LETTERS_POSITION_TOP[i]),
					'left': Resizer_px(self.resizer, LETTERS_POSITION_LEFT[i]),
					'font-size': Resizer_letter(self.resizer, 22),
					'color': color
				}
			),
			self.scale[i][0]
		));
	}
	self.div.append(jdiv(
		dict__add__(
			TITLE_STYLE,
			{
				'top': Resizer_px(self.resizer,2),
				'left': Resizer_px(self.resizer,7),
				'font-size': Resizer_letter(self.resizer, 15)
			}
		),
		self.title1
	));
	self.div.append(jdiv(
		dict__add__(
			TITLE_STYLE,
			{
				'top': Resizer_px(self.resizer,292),
				'left': Resizer_px(self.resizer,7),
				'font-size': Resizer_letter(self.resizer, 15)
			}
		),
		self.title2
	));
	return self.div;
}

/* endclass LeftFrame */

/* class DpeCanvas */

function DpeCanvas(type, value, resizer, unit){
	var obj = new Object();
	DpeCanvas__init__(obj, type, value, resizer, unit);
	return obj;
}

function DpeCanvas__init__(self, type, value, resizer, unit){
	/* type: "energy" or "gg" */
	/* value: a string representing a number or a letter from 'A' to 'G' */ 
	self.value = value;
	self.type = type;
	self.resizer = resizer;
	self.unit = unit;
	self.div = null;
	if(type == 'gg'){
		self.scale = GG_SCALE;
		self.background = gg_background(Resizer_resize(resizer, HEIGHT));
	}
	else{
		self.scale = ENERGY_SCALE;
		self.background = ENERGY_BACKGROUND;
	}
	self.title1 = undefined;
	self.title2 = undefined;
	self.title3 = 'Logement';
}

function DpeCanvas_create(self){
	self.div = jdiv({
		'position': 'relative',
		'border-style': 'solid',
		'border-width': '1px',
		'border-color': 'black',
		'background-color': 'white'
	});

	var dpe_value = DpeValue(self.scale, self.value);
	var dpe_value_div = DpeValue_create_pointer(dpe_value, self.resizer, self.unit);
	var pointer_width = DpeValue_get_div_width(dpe_value);

	var title3_div = jdiv(
		dict__add__(
			TITLE3_STYLE,
			{
				'font-size': Resizer_letter(self.resizer, 13),
				'left': (Resizer_resize(self.resizer, LEFT_FRAME_WIDTH) + 1) + "px",
				'top': Resizer_px(self.resizer, 2),
				'letter-spacing': Resizer_letter(self.resizer, 0)
			}
		),
		self.title3
	)
	title3_div.width(Resizer_resize(self.resizer,pointer_width));
	self.div.width(Resizer_resize(self.resizer, LEFT_FRAME_WIDTH + pointer_width) + 1)
	self.div.height(Resizer_resize(self.resizer, HEIGHT));
	self.div.append(LeftFrame_create(LeftFrame(self.scale, self.background, self.resizer, self.title1, self.title2)));
	self.div.append(title3_div);
	self.div.append(dpe_value_div);
	return self.div;
}

function pxParseFloat(strg){
	var t = typeof(strg);
	if(t == "number")
		return strg;
	if(t != "string")
		throw("Please enter a number or a string representing a number. " + strg + "is neither.");
	var rg = /^(\d+(\.\d+)?) ?(px)?$/;
	var groups = rg.exec(strg);
	if(groups)
		return parseFloat(groups[1]);
	else
		throw(strg + " can't be parsed into a float. Please enter a valid number, or a string representing a number. Examples:\n220\n22.5\n312px");
}

function Dpe(){
	this.energy_title1 = "Consommation &eacute;nerg&eacute;tique";
	this.energy_title2 = 'Unit&eacute;: kWh<span style="position:relative;top:1px;font-size:80%">EP</span>/m&sup2;.an';
	this.gg_title1 = 'Emissions de GES';
	this.gg_title2 = 'Unit&eacute;: kg<span style="position:relative;top:1px;font-size:80%">&eacute;qCO2</span>/m&sup2;.an';
	this.energy_title3 = "";
	this.gg_title3 = "";
	this.canvas_height = DEFAULT_HEIGHT;
	this.sticker_height = STICKER_HEIGHT;
	this.title_regex = /^((energie|energy|gg|ges)(-?sticker)?) *: *([a-z]|(\d+(\.\d+)?))$/i;
	this.create_div = Dpe_create_div;
	this.all = Dpe_all;
	this.divs = Dpe_all;
	this.set_img_folder = function(img_folder){
		IMG_FOLDER = img_folder||IMG_FOLDER;
	}
	this.tag_attribute = 'title';
}

function Dpe_create_div(type, value, attr){
	var unit, ht, tp;
	tp = valid_type(type);
	var attr = attr || {};
	if(tp == 'energy' || tp == 'gg'){
		ht = ('height' in  attr)?pxParseFloat(attr['height']):this.canvas_height;
		if('unit' in attr)
			unit = attr['unit'];
		else{
			if(tp == 'gg')
				unit = (this.gg_unit == undefined)?'kg<sub>&eacute;qCO2</sub>/m&sup2;.an':this.gg_unit;
			else
				unit = (this.energy_unit == undefined)?'':this.energy_unit;
		}
		dpe_canvas = DpeCanvas(tp, value, Resizer(ht/HEIGHT), unit);
		dpe_canvas.title1 = ('title1' in attr)?attr['title1']:((tp == 'energy')?this.energy_title1:this.gg_title1);
		dpe_canvas.title2 = ('title2' in attr)?attr['title2']:((tp == 'energy')?this.energy_title2:this.gg_title2);
		dpe_canvas.title3 = ('title3' in attr)?attr['title3']:((tp == 'energy')?this.energy_title3:this.gg_title3);
		return DpeCanvas_create(dpe_canvas);
	}
	else{
		ht = ('height' in  attr)?pxParseFloat(attr['height']):this.sticker_height;
		return DpeValue_create_sticker(DpeValue(type_to_scale(tp), value), Resizer(ht/STICKER_HEIGHT));
	}			
}

function Dpe_insert(self){
	return function(index, div){
		var container = jQuery(div);
		var title = container.attr('title');
		var match = self.title_regex.exec(title);
		if(match){
			var type = match[1];
			var value = match[4];
			container.empty();
			container.append(self.create_div(type, value));
			
		}
	}
}

function Dpe_all(){
	jQuery.each(jQuery('div[' + this.tag_attribute + ']'), Dpe_insert(this));
}

return new Dpe();
}();

dpe.all();