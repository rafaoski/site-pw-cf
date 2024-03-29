<?php namespace ProcessWire;

/**
 * wire/modules/Inputfield/InputfieldForm.module
 * wire/core/InputfieldWrapper.php
 *
 * https://processwire.com/api/ref/functions/wire-mail/
 * https://github.com/leonidlezner/pwtemplate/wiki/Rendering-a-simple-contact-form-in-ProcessWire
 * https://www.spiria.com/en/blog/website-creation/tutorial-how-to-create-a-form-in-processwire/
 * https://processwire.com/talk/topic/2089-create-simple-forms-using-api/
 * https://processwire.com/talk/topic/2089-create-simple-forms-using-api/page/6/
 *
*/

// E-Mail Adress
$from = $eMail;

// Privacy Policy
$privacyPage = $privacyPage;

// Translatable Text
$textName = setting('contact-name');
$textEmail = setting('contact-email');
$textSubject = setting('contact-subject');
$textAcceptPrivacy = setting('contact-acceptprivacy');
$textMessage = setting('contact-message');
$textYourMessage = setting('contact-yourmessage');
$textSuccessMessage = setting('contact-succes');
$textPrivacyInfo =  setting('contact-privacy');
$errorFix = setting('contact-errorfix');
$textReturnContact = setting('contact-return');
$textSubmit = setting('contact-submit');
$textCsrfFailed = setting('csrf-failed');

// Icons
$errorIcon = icon('exclamation-circle', ['color' => 'var(--color-accent)']);
$downIcon = ' ' . icon('arrow-circle-down', ['size' => .7]);

	// Initialization of the form.
	$form = modules()->get("InputfieldForm"); // the form module
	$form->action = "./"; // we submit on the same page
	$form->method = "post"; // the method of submitting values
	$form->attr("name+id", "contact_form"); // it will be as much the name as the id
	$form->attr("class", "contact-form"); //any CSS class.

	// Set markup for form elements
	$form->setMarkup(array(
		'list' => "<ul style='list-style: none; padding: 20px; background: var(--color-contrast-lower);' {attrs}>{out}</ul>",
		'item' => "<li {attrs}>{out}</li>",
		'item_label' => "<label class='InputfieldHeader ui-widget-header{class}' for='{for}'>{out}</label>",
		'item_label_hidden' => "<label class='InputfieldHeader InputfieldHeaderHidden ui-widget-header{class}'><span>{out}</span></label>",
		'item_content' => "<div class='InputfieldContent {class}'>{out}</div>",
		'item_error' => "<p class='InputfieldError ui-state-error'><span>{out} $errorIcon</span></p>",
		'item_description' => "<p class='description' style='margin: 0'>{out}</p>",
		'item_head' => "<h2>{out}</h2>",
		'item_notes' => "<p class='notes'>{out}</p>",
		'item_icon' => "<i class='fa fa-fw fa-{name}'></i> ",
		'item_toggle' => $downIcon,
	));

	$form->setClasses(array(
		'form' => 'contact_forms', // additional clases for InputfieldForm (optional)
		'list' => 'Inputfields',
		'list_clearfix' => 'ui-helper-clearfix',
		'item' => 'margin-top-sm Inputfield {class} Inputfield_{name} ui-widget',
		'item_label' => 'text-lg', // additional classes for InputfieldHeader (optional)
		'item_content' => '',  // additional classes for InputfieldContent (optional)
		'item_required' => 'InputfieldStateRequired', // class is for Inputfield
		'item_error' => 'ui-state-error InputfieldStateError', // note: not the same as markup[item_error], class is for Inputfield
		'item_collapsed' => 'InputfieldStateCollapsed',
		'item_column_width' => 'InputfieldColumnWidth',
		'item_column_width_first' => 'InputfieldColumnWidthFirst',
		'item_show_if' => 'InputfieldStateShowIf',
		'item_required_if' => 'InputfieldStateRequiredIf'
		// ALSO:
		// InputfieldAnything => array( any of the properties above to override on a per-Inputifeld basis)
	));

	// Honeypot
	$form->appendMarkup = "<input name='firstname' type='text' id='firstname' autocomplete='off' class='hide-robot'>";

	// Text field ( name ).
	$f = modules()->get("InputfieldText");
	$f->set("label", $textName);
	$f->attr("name+id", "name");
	$f->attr("maxlength", 50);
	$f->attr("class", "form-control");
	$f->attr("placeholder", $textName);
	$f->attr("required", "required");
	$f->required(true);
	$form->add($f);

	// E-mail address field ( e-mail ).
	$f = wire("modules")->get("InputfieldEmail");
	$f->set("label", $textEmail);
	$f->attr("name+id", "email");
	$f->attr("maxlength", 60);
	$f->attr("class", "form-control");
	$f->attr("placeholder", $textEmail);
	$f->attr("required", "required");
	$f->required = true;
	$form->add($f);

	// Textarea ( Message ).
	$f = modules()->get("InputfieldTextarea");
	$f->set("label", $textMessage);
	$f->attr("name+id", "message");
	$f->attr("rows", 7);
	$f->attr("class", "form-control width-100%");
	$f->attr("placeholder", $textMessage);
	$f->attr("required", "required");
	$f->required(true);
	$form->add($f);

	// Checkbox field.
	$f = modules()->get("InputfieldCheckbox");
	$f->set("label", $textAcceptPrivacy);
	$f->set("description", "$textPrivacyInfo [$privacyPage->title]($privacyPage->url)");
	$f->attr("name+id", "accept_privacy");
	$f->attr("style", "width: 20px; height: 20px; margin: 10px");
	$f->attr("required", "required");
	$f->required(true);
	$form->add($f);

  	// Submit.
	// A form is nothing without a send button!
	$f = modules()->get("InputfieldSubmit");
	$f->attr("id", "pf-submit-btn");
	$f->attr("name", "submit_form");
	$f->attr("value", $textSubmit);
	$f->attr("class", "btn btn--primary margin-y-sm");
	$form->add($f);

 	if($input->post('submit_form')) {

		// Honeypot
		if( input()->post('firstname') ) {
			session()->redirect( pages('/http404/')->url );
		}

		// when processing form (POST request), check to see if token is present ( https://processwire.com/api/ref/session/c-s-r-f/ )
		if(!$session->CSRF->hasValidToken()) {
			session()->contactFormMessage = "<h3 style='color: var(--color-error)'>$textCsrfFailed</h3>";
			session()->redirect( page()->url );
			// throw new WireException('CSRF check failed!');
		}

		// form submitted
		$form->processInput($input->post);

		// errors
		$errors = $form->getErrors();

		if(count($errors)) {
			echo "<h3 style='color: var(--color-error)'>$errorFix</h3>";
			echo $form->render();
		} else {

		// To
		$to = sanitizer()->email( input()->post('email') );

		// Sanitize values
		$name = sanitizer()->text( input()->post('name') );
		$acceptPrivacy = sanitizer()->text( input()->post('accept_privacy') );
		$acceptPrivacy = $acceptPrivacy ? 'Accept' : 'No Accept';
		$message = sanitizer()->text( input()->post('message') );

		// Subject
		$subject = sprintf($textSubject, $name, $to);

        // Prepare message
		$bodyHml  =  "<ul style='list-style: none'>";
		$bodyHml .=  "<li><h3>$textName: $name</h3></li>";
		$bodyHml .=  "<li><h3>$textEmail: $to</h3></li>";
		$bodyHml .=  "<li><h3>$textAcceptPrivacy: $acceptPrivacy</h3></li>";
		$bodyHml .=  "</ul>";
		$bodyHml .=  "<h3>$textMessage:</h3> <p class='text-md'>$message</p>";

		// Specify body and/or bodyHTML in $options array (perhaps with other options):
		$options = ['bodyHTML' => $bodyHml];
		wireMail($to, $from, $subject, $options); // Submit message

		// Save Message
		files()->include('_save-message',
		[
			'bodyHtml' => $bodyHml,
			'name' => $name,
			'email' => $to,
			// 'ip' => session()->getIP(),
		]);

		// Success Message
		session()->contactFormMessage = '<h3>' . sprintf($textSuccessMessage, $name, $to) . '</h3>';
		session()->contactFormMessage .= "<h3>$textYourMessage:</h3>";
		session()->contactFormMessage .= "<div class='component-text text-sm'>$bodyHml</div>";
		session()->contactFormMessage .= "<a class='btn btn--lg btn--primary' href='./'>$textReturnContact</a>";

		// Redirect
		session()->redirect( page()->url );

		}

	} else {

	// form not submitted, just display it
		echo $form->render();
	}
