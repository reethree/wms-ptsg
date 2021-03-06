/**
 * @file
 * Javascript helper functions.
 *
 * All DecimaERP code is copyright by the original authors and released under the GNU Aferro General Public License version 3 (AGPLv3) or later.
 * See COPYRIGHT and LICENSE.
 */


/**
 * After a new row is inserted, the grid will be reloaded and the first row will be selected.
 *
 * @param string sortname
 * 	The column according to which the data is to be sorted, default: id.
 * @param string sortorder
 * 	The sorting order asc for ascending or desc for descending, default: desc.
 * @param string page
 * 	The page to ..., default: 1.
 *
 *  @returns void
 */
$.fn.reloadAfterRowInserted = function(sidx, sord, page)
{
	var jqGrid = this;

	sidx = sidx || 'id';

	sord = sord || 'desc';

	page = page || 1;

	jqGrid.setGridParam({'sortname':sidx, 'sortorder':sord, 'page':page}).trigger('reloadGrid');

	// setTimeout(function()
	// {
	// 	jqGrid.setSelection(1);
	// },500);
};

/**
 * Get cell id of all grid selected rows.
 *
 * @param string columnIdName
 * 	Grid Column Id name, default: id.
 *
 *  @returns array
 */
$.fn.getSelectedRowsIdCell = function(columnIdName)
{
	var idArray = [], row, grid;

	columnIdName = columnIdName || 'id';

	grid = this;

	$.each(this.jqGrid('getGridParam', 'selarrrow'), function( index, value )
	{
		row = grid.getRowData(value);

		idArray.push(row[columnIdName]);
	});

	return idArray;
};

/**
 * Get cell id of selected row.
 *
 * @param string columnIdName
 * 	Grid Column Id name, default: id.
 *
 *  @returns array
 */
$.fn.getSelectedRowId = function(columnIdName)
{
	var	row = this.getRowData(this.jqGrid('getGridParam', 'selrow')),

	columnIdName = columnIdName || 'id';

	return row[columnIdName];
};


/**
 * Populate multiselect
 *
 * @param array options
 * 	An array of objects as follows: [{"value":"value  0", "text":"text 0"}, {"value":"value 1", "text":"text 1"},â€¦]
 * @param object selected
 * 	An object as follows: {"value 0":"text 0", "value 1":"text 1"}
 * @param boolean refresh
 *
 *  @returns array
 */
$.fn.populateMultiSelect = function(options, selected, refresh)
{
	options = options || {};
	selected = selected || [];
	refresh = (refresh == undefined ? true : false);
	select = this;
	select.find('option').remove();

	$.each(options, function( index, option )
	{
		select.append('<option ' + (selected[option.value]?'selected="selected"':'') + 'value="' + option.value + '">' + option.text + '</option>');
	});

	if(refresh)
	{
		select.multiselect('refresh');
	}
};

/**
* Get selected options from a multiselect
*
*  @returns array
*/
$.fn.getMultiselectSelectedOptionsId = function()
{
	var idArray = [];

	$.each(this.find(":selected"), function( index, element )
	{
		idArray.push(element.value);
	});

	return idArray;
};

/**
 * Get selected options id from a multiselect object.
 *
 * @param array options
 * 	An array of objects as follows: [{"value":"value  0", "text":"text 0"}, {"value":"value 1", "text":"text 1"},â€¦]
 *
 * @returns array
 *  An array as follows: ["value 0", "value 1",â€¦]
 */
function getSelectedOptionsId(optionElements)
{
	var idArray = [];

	$.each(optionElements, function( index, option )
	{
		idArray.push(option.value);
	});

	return idArray;
}

/**
 * Select first item of an autocomplete element.
 *
 *  @returns void
 */
$.fn.selectFirstItem = function(trigger)
{
	var items = $(this.autocomplete( "option", "source" ));

	trigger = (trigger == undefined ? true : trigger);

	if($.isPlainObject(items[0]))
	{
		this.data('ui-autocomplete')._trigger('select', 'autocompleteselect', {trigger: trigger, item: {label:items[0].label, value:items[0].value}});
	}
	else
	{
		this.val(items[0]);
	}
};

/**
 * Verify is the value of an autocomplete element is valid.
 *
 *  @returns true if valid, false otherwise
 */
$.fn.isAutocompleteValid = function()
{
	if(this.isEmpty())
	{
		return false;
	}

	var value = $(this).val().toLowerCase(), valid = false, autocomplete = this;

	$.each(this.autocomplete( "option", "source" ), function(index, element)
	{
		if($.isPlainObject(element))
		{
			// console.log(element);
			if(element.label.toLowerCase() == value || element.value.toString().toLowerCase() == value)
			{
				valid = true;
				autocomplete.data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item: {label:element.label, value:element.value}});
				return false;
			}
		}
		else
		{
    	if(element.toLowerCase() == value)
			{
				valid = true;
				autocomplete.val(element);
				return false;
      }
   	}
 	});

 	return valid;
};


/**
 * Populate form fields.
 *
 * @param string object
 * 	 The index must correspond to the form element id and the value will be set as the form element value.
 * @param string prefix
 * 	Elements id prefix.
 *
 * @returns void
 */
function populateFormFields(object, prefix)
{
	prefix = prefix || '';

	$.each(object, function( index, value )
	{
		var element = prefix +  index.replace(/_/g,'-');

		if($('#' + element).is("input") || $('#' + element).is("textarea"))
		{
			switch ($('#' + element).attr('type'))
			{
				case 'checkbox': //missing radio, autocomplete, multiselect
					if((value == 1 && !$('#' + element).is(":checked")) || (value == 0 && $('#' + element).is(":checked")))
					{
						$('#' + element).click();
					}
					break;
				default:
					$('#' + element).val(value);
					break;
			}
		}

		if($('#' + element).is("select"))
		{
			$('#' + element).val(value);
		}
	});
};

/**
 * Create an object from form fields.
 * Use: $('#formId').formToObject();
 *
 * @returns object
 */
$.fn.formToObject = function(removePrefix)
{
	var object = {}, removePrefix = removePrefix || '', index;

	this.find('input,select,textarea').each(function()
	{
		if($(this).attr("type") == 'radio')
		{
			index = $(this).attr('name');
		}
		else
		{
			index = $(this).attr('id') || $(this).attr('name');
		}

		index = index.replace(removePrefix, '');
		index =	index.replace(/-/g,'_');

		if($(this).is("input") && $(this).attr("type") == 'checkbox')
		{
			object[index] = $(this).is(':checked')?'1':'0';
		}
		else if($(this).is("input") && $(this).attr("type") == 'radio')
		{
			if($(this).is(':checked'))
			{
				object[index] = $(this).val();
			}
		}
		else
		{
			object[index] = $(this).val();
		}
	});

	return object;
};

/**
 * Clean all form fields.
 * Use: $('#formId').cleanForm();
 *
 * @returns void
 */
$.fn.cleanForm = function()
{
	this.find('input[type=text],input[type=password],input[type=hidden],textarea').each(function()
	{
		if($(this).attr('name') != '_token')
		{
			$(this).val('');
		}
	});

	this.find('select').each(function()
	{
		$(this).val($('#'+$(this).attr('id')+' option:first').val());
	});

	this.find('input[type=checkbox]').each(function()
	{
		 $(this).removeAttr('checked');
	});

	this.find('.has-error').each(function()
	{
		$(this).removeClass('has-error');
	});

	this.find('.has-success').each(function()
	{
		$(this).removeClass('has-success');
	});

	this.find('.help-message-text').remove();
};

/**
 * Display alert messages after an element.
 * Use: $('#id').showAlert();
 *
 * @param string cssClass
 * 	Expected values: alert-success, alert-info, alert-warning, alert-danger.
 * @param string textAlert
 * 	Text to be shown.
 * @param integer delay
 * 	An integer indicating the number of milliseconds to delay fadeOut, a null value is also accepted.
 *
 *
 */
$.fn.showAlertAfterElement = function (cssClass, textAlert, delay)
{
	$("#" + this.attr("id") + "-alert").hide();
	$("#" + this.attr("id") + "-alert").alert('close');

	this.after('<div id="' + this.attr("id") + '-alert" class="alert alert-block ' + cssClass + ' fade in"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>' + textAlert + '</div>');

//	$.scrollTo($("#" + this.attr("id") + "-alert").position());
	$("#" + this.attr("id") + "-alert").alert();

	if(delay)
	{
		$("#" + this.attr("id") + "-alert").delay( delay ).fadeOut();
	}
};

/**
 * Display alert messages as first child of an element.
 * Use: $('#id').showAlert();
 *
 * @param string cssClass
 * 	Expected values: alert-success, alert-info, alert-warning, alert-danger.
 * @param string textAlert
 * 	Text to be shown.
 * @param integer delay
 * 	An integer indicating the number of milliseconds to delay fadeOut, a null value is also accepted.
 *
 *
 */
$.fn.showAlertAsFirstChild = function (cssClass, textAlert, delay)
{
	$("#" + this.attr("id") + "-alert").hide();
	$("#" + this.attr("id") + "-alert").alert('close');

	this.prepend('<div id="' + this.attr("id") + '-alert" class="alert alert-block ' + cssClass + ' fade in"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>' + textAlert + '</div>');

	$.scrollTo($("#" + this.attr("id") + "-alert").position());
	$("#" + this.attr("id") + "-alert").alert();

	if(delay)
	{
		$("#" + this.attr("id") + "-alert").delay( delay ).fadeOut();
	}
};

/**
 * Display a yes/no question as first child of an element.
 * Use: $('#id').showAlert();
 *
 * @param string question
 * 	Question to be shown
 * @param string functionToCallIfYes
 * 	Function to be called if user answer yes
 * @param string functionToCallIfNo
 * 	Function to be called if user answer no
 * @param string cssClass
 * 	Expected values: alert-success, alert-info, alert-warning, alert-danger. Default value: alert-info.
 *
 *
 */
$.fn.showYesNoQuestionAsFirstChild = function (question, functionToCallIfYes, functionToCallIfNo, cssClass)
{
	cssClass = cssClass || 'alert-info';

	$("#" + this.attr("id") + "-yes-no-question").hide();
	$("#" + this.attr("id") + "-yes-no-question").alert('close');

	html = '<div id="' + this.attr('id') + '-yes-no-question" class="alert alert-block ' + cssClass + ' fade in" style="text-align: center;">' + question;
	html += '<div class="btn-group" style="margin-top: 10px;"><button class="btn btn-success" type="button" onclick="' + functionToCallIfYes + '();"><i class="fa fa-check"></i> ' + lang.yes + '</button><button class="btn btn-danger" type="button" onclick="' + functionToCallIfNo + '();"><i class="fa fa-times"></i> ' + lang.no + '</button></div></div>';
	this.prepend(html);

	$.scrollTo($("#" + this.attr("id") + "-yes-no-question").position());
	$("#" + this.attr("id") + "-yes-no-question").alert();
};

/**
 * Show server errors by field in a form.
 *
 * @param array fieldValidationMessages
 * 	An array of objects as follows: {fieldId0:message0, fieldId1:message1,â€¦}
 *
 * @returns void
 */
$.fn.showServerErrorsByField = function(fieldValidationMessages, prefix)
{

	prefix = prefix || '';

	count = 0;

	$.each(fieldValidationMessages, function( field, message )
	{
		$('#' + prefix + field).jqMgValDisplayMessage('has-error', message);

		if(count == 0)
		{
				$.scrollTo($('#' + prefix + field).position());
		}

		count++;
	});

	this.find('.has-error').effect('shake',null,600);
};

/**
 * Serializes a form into an object.
 * Disabled input elements are ignored.
 * Use: $('#formId').serializeObject();
 *
 *  @returns object
 */
$.fn.serializeObject = function()
{
	var obj = {};

	$.each( this.serializeArray(), function(i,o)
	{
		var n = o.name,
		v = o.value;

        obj[n] = obj[n] === undefined ? v
          : $.isArray( obj[n] ) ? obj[n].concat( v )
          : [ obj[n], v ];
    });

    return obj;
};

/**
 * Check if the value of an element is empty.
 * Use: $('#inputId').isEmpty();
 *
 * @returns true if is empty, false otherwise.
 */
$.fn.isEmpty = function()
{
	if(this.is("select"))
	{
		return this.val() == "";
	}
	else
	{
		return $.trim(this.val()) == "";
	}
};

/**
 * Check if an element han a specific attribute.
 * Use: $('#id').hasAttr('attribute');
 *
 * @returns true if the element has the attribute, false otherwise.
 */
$.fn.hasAttr = function(name) {
   return this.attr(name) !== undefined;
};

/**
 * Key press cross browser compatibility
 * Firefox...
 *
 * @returns ...
 */
function keyPressCrossBrowserCompatibility(event)
{
	//Fix firefox
	switch (event.keyCode)
	{
		case 8://backspace
		case 9://tab
		case 33://Re pÃ¡g.
		case 34://Av PÃ¡g.
		case 35://fin
		case 36://inicio
		case 45://insert
		case 46://Supr
			return true;
			break;
		default:
			return false;
	}
	//console.log("entre");
	//console.log("keycode: "+event.keyCode+" charcode: "+event.charCode);
};

/**
 * Enable a group of buttons within a container.
 *
 * @returns void
 */
$.fn.enableButtonGroup = function()
{
	this.find('button').each(function()
	{
            $(this).removeAttr('disabled');
	});
};

$.fn.enableFormGroup = function()
{
	this.find('input').each(function()
	{
            if($(this).attr('name') != 'working_hours'){
                $(this).removeAttr('disabled');
            }   
	});
        
        $(".select2").prop("disabled", false);
        $("textarea").removeAttr('disabled');
};

/**
 * Disabled a group of buttons within a container.
 *
 * @returns void
 */
$.fn.disabledButtonGroup = function()
{
	this.find('button').each(function()
	{
            $(this).attr('disabled','disabled');
	});
};

$.fn.disabledFormGroup = function()
{
	this.find('input').each(function()
	{
            $(this).attr('disabled','disabled');
	});
        
        $(".select2").prop("disabled", true);
        $("textarea").attr('disabled','disabled');
};

/**
 * Enable all elements on screen.
 *
 * @returns void
 */
function enableAll()
{
	$('#main-panel-fieldset').removeAttr('disabled');
	$('#user-apps-panel-fieldset').removeAttr('disabled');
}

/**
 * Disabled all elements on screen.
 *
 * @returns void
 */
function disabledAll()
{
	$('#main-panel-fieldset').attr('disabled','disabled');
	$('#user-apps-panel-fieldset').attr('disabled','disabled');
}

/**
 * Change windows URL.
 *
 *  @returns void
 */
function changeWindowsUrl(url)
{
	State = History.getState();

	if(State.url != ($('#app-url').val() + url))
	{
		History.pushState(null, $('a[appurl=\'' + url +'\']').html(), $('#app-url').val() + url);
	}
}

/**
 * Calculate percent
 *
 * @param array chartData
 * 	An array of objects as follows: [ { label: $label0, value: $value0 },â€¦ ]
 *
 *  @returns object
 */
function calculatePercent(chartData, changeValueForPercent)
{
	var sum = 0;

	changeValueForPercent = changeValueForPercent || false;

	for (var x in chartData)
	{
	    sum += chartData[x].value;
	}

	for (var x in chartData)
	{
		if(changeValueForPercent)
		{
			chartData[x].value = (chartData[x].value / sum * 100).toFixed(2);
		}
		else
		{
			chartData[x].description = (chartData[x].value / sum * 100).toFixed(2) + '%';
		}
	}

	return chartData;
}

/**
 * Bind the custom "on enter" event to an element.
 *
 *  @returns void
 */
$.fn.onEnter = function(func)
{
    this.bind('keypress', function(e)
    {
        if (e.keyCode == 13) func.apply(this, [e]);
    });

    return this;
};

/**
 * Custom autocomplete validator for jQuery MG Validation Plugin
 *
 * @returns void
 */
var jqMgValAutocompleteValidator = function($element)
{
	$element.on( "autocompletechange", function( event, ui )
	{
		if ( ui.item )
		{
			$element.jqMgValDisplayMessage('has-success','');
		}

		if($element.isEmpty())
		{
			if($element.attr('data-mg-required') != undefined)
			{
				$element.jqMgValDisplayMessage('has-error', '');
			}
			else
			{
				$element.jqMgValDisplayMessage('has-success','');
			}

			return;
		}

		var value = $element.val().toLowerCase(), valid = false, autocomplete = this;

		$.each($element.autocomplete( "option", "source" ), function(index, element)
		{
				if($.isPlainObject(element))
				{
					elementLabel = $.isNumeric(element.label)?element.label.toString().toLowerCase():element.label.toLowerCase();
					elementValue = $.isNumeric(element.value)?element.value.toString().toLowerCase():element.value.toLowerCase();

					if (elementLabel == value || elementValue == value)
					{
								valid = true;
								$(autocomplete).data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item: {label:element.label, value:element.value}});
								return false;
						}
				}
				else
				{
					if (element.toLowerCase() == value)
					{
								valid = true;
								$(autocomplete).val(element);
								return false;
						}
				}
		});

		if ( valid )
		{
			$element.jqMgValDisplayMessage('has-success', '');
		}
		else
		{
			$element.jqMgValDisplayMessage('has-error', lang.autocompleteValidation);;
		}

	});

	$element.focusout(function()
	{
		$(this).trigger('autocompletechange', ['']);
	});
};

/**
* Build Pie Chart
*
* @param array options
* 	An array of array as follows: [['label0', 'value0'], ['label0', 'value0'], â€¦]
* @param boolean showLegend
* @param string showLegend
* @param boolean showDataLabels
* @param string dataLabels
*
*  @returns void
*/
$.fn.buildPieChart = function(data, showLegend, legendLocation, showDataLabels, dataLabels, placement, title)
{
		showLegend = showLegend || true;

		legendLocation = legendLocation || 's';

		showDataLabels = showDataLabels || true;

		dataLabels = dataLabels || 'value';

		placement = placement || 'inside';

		title = title || '';

		if(dataLabels == 'custom')
		{
			total = 0;
    	$(data).map(function(){total += this[1];});
			dataLabels = $.makeArray($(data).map(function(){return this[1] + " " + Math.round(this[1]/total * 100) + "%";}));
		}

		return jQuery.jqplot (this.attr('id'), [data],
			{
				title:{text: title},
				seriesDefaults: {
					// Make this a pie chart.
					renderer: $.jqplot.PieRenderer,
					trendline:{ show:false },
					rendererOptions: {
						// Put data labels on the pie slices.
						// By default, labels show the percentage of the slice.
						showDataLabels: showDataLabels,
						dataLabels: dataLabels
					}
				},
				legend: { show: showLegend, location: legendLocation, placement: placement }
			}
		);
};