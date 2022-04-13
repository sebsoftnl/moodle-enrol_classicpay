<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language file for enrol_classicpay, NL
 *
 * File         enrol_classicpay.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'ClassicPay';
$string['pluginname_desc'] = 'Deze plugin maakt het mogelijk om een cursus te kopen met de PAYNL gateway';
$string['promo'] = 'Classicpay aanmeldplugin voor Moodle';
$string['promodesc'] = 'Deze plugin is geschreven door Sebsoft Managed Hosting & Software Development
(<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
{$a}<br /><br />';
$string['mailadmins'] = 'E-mail admin';
$string['nocost'] = 'Er zitten geen kosten aan deze cursus';
$string['currency'] = 'Valuta';
$string['cost'] = 'Inschrijf kosten';
$string['vat'] = 'BTW';
$string['vat_help'] = 'BTW percentage voor cursus kosten (gegeven cursuskosten zijn incl. BTW).';
$string['assignrole'] = 'Toekennen rol';
$string['mailstudents'] = 'E-mail studenten';
$string['mailteachers'] = 'E-mail leraren';
$string['expiredaction'] = 'Enrolment verloop actie';
$string['expiredaction_help'] = 'Selecteer actie uit te voeren wanneer de gebruiker de inschrijving verloopt. Houdt u er rekening mee dat sommige gebruikersgegevens en instellingen kunnen worden verwijderd.';
$string['status'] = 'Toestaan ClassicPay inschrijvingen';
$string['status_desc'] = 'Sta gebruikers toe om ClassicPay gebruiken om in te schrijven in een cursus standaard.';
$string['defaultrole'] = 'Standaard roltoewijzing';
$string['defaultrole_desc'] = 'Selecteer rol die de gebruikers moeten worden toegekend tijdens ClassicPay inschrijvingen';
$string['enrolenddate'] = 'Eind datum';
$string['enrolenddate_help'] = 'Indien ingeschakeld, kunnen gebruikers worden ingeschreven tot deze datum.';
$string['enrolenddaterror'] = 'Inschrijving einddatum kan niet eerder dan startdatum';
$string['enrolperiod'] = 'Inschrijving duur';
$string['enrolperiod_desc'] = 'Standaard lengte dat een inschrijving geldig is. Indien ingesteld op nul, zal de inschrijving voor onbeperkte tijd zijn';
$string['enrolstartdate'] = 'Start datum';
$string['enrolstartdate_help'] = 'Indien ingeschakeld, kunnen gebruikers worden ingeschreven vanaf deze datum.';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['title:returnpage'] = 'Betaal Status';
$string['enabled'] = 'Ingeschakeld';
$string['enabled_desc'] = 'Wanneer ingeschakkeld kan deze enrolment plugin ingesteld worden bij een cursus.';
$string['htmlonthankyoupage'] = 'HTML fragment op bedankpagina';
$string['htmlonthankyoupage_desc'] = 'HTML fragment dat weergegeven wordt onderaan de bedankpagina, na het kopen van de cursus, om de gebruiker een warmer welkom bij de cursus te geven.';
$string['expiredaction'] = 'Inschrijving verloop actie';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['expirymessageenrollersubject'] = 'Melding voor het vervallen van de aanmelding';
$string['expirymessageenrollerbody'] = 'De aanmelding in cursus \'{$a->course}\' zal binnen {$a->threshold} vervallen voor volgende gebruikers:

{$a->users}

Ga naar {$a->extendurl} om hun aanmelding te verlengen.';
$string['expirymessageenrolledsubject'] = 'Melding voor het vervallen van de aanmelding';
$string['expirymessageenrolledbody'] = 'Beste {$a->user},

Je aanmelding in cursus \'{$a->course}\' gaat vervallen op {$a->timeend}.

ls je hier een probleem mee hebt, neem dan contact op met {$a->enroller}.';
$string['settings'] = 'Instellingen';
$string['purchase'] = 'Koop cursus';
$string['name'] = 'Naam';
$string['minimum'] = 'Minimaal';
$string['maximum'] = 'Maximaal';
$string['button:pay'] = 'Afrekenen';
$string['enrol:fail'] = 'Je bent niet aangemeld voor deze cursus.';
$string['enrol:fail:tx'] = 'Je transactie status is: {$a->statusname}.';
$string['enrol:ok'] = 'Bedankt voor je aankoop.<br> Je bent nu aangemeld voor cursus: {$a->fullname}';
$string['enrol:already'] = 'Je bent al aangemeld voor cursus: {$a->fullname}';
$string['payment:cancelled'] = 'Je hebt je betaling geannuleerd voor cursus: {$a->fullname}';

$string['title:cancelpage'] = 'Betaling geannuleerd';
$string['title:transactions'] = 'Clasicpay - transacties';
$string['title:enrolments'] = 'Classicpay - enrolments';
$string['title:service'] = 'Classicpay - account informatie';
$string['title:legal'] = 'Classicpay - aansprakelijkheidsinformatie';
$string['title:couponmanager'] = 'Coupon beheer';
$string['title:couponmanager:delete'] = 'Coupon beheer - verwijderen';
$string['title:couponmanager:edit'] = 'Coupon beheer - bewerken';
$string['title:couponmanager:details'] = 'Coupon beheer - details';
$string['paynlsettings'] = 'PAYNL Account merchant instellingen';
$string['paynlsettings_desc'] = 'Hieronder dient je de PAYNL merchant instellingen te configureren die je toestaan betalingen te initialiseren.';
$string['paynlapitoken'] = 'PAYNL API token';
$string['paynlapitoken_desc'] = 'De token is noodzakelijk om met de PAYNL API te kunnen communiceren';
$string['paynlserviceid'] = 'PAYNL Service ID';
$string['paynlserviceid_desc'] = 'De Service ID is nodig om de service bij de PAYNL API te identificeren';
$string['paynlmerchantid'] = 'PAYNL Merchant ID';
$string['paynlmerchantid_desc'] = 'De Merchant ID is nodig om de merchant bij de PAYNL API te identificeren';
$string['th:courseid'] = 'Cursus';
$string['th:code'] = 'Code';
$string['th:discount'] = 'Korting';
$string['th:percentage'] = 'Percentage';
$string['th:validfrom'] = 'Geldig van';
$string['th:validto'] = 'Geldig tot';
$string['th:numused'] = '#Gebruikt';
$string['th:maxusage'] = 'Max gebruik';
$string['th:txid'] = 'Transactie ID';
$string['th:action'] = 'Actie(s)';
$string['th:status'] = 'Status';
$string['th:user'] = 'Gebruiker';
$string['th:paymentcreated'] = 'Transactie gestart';
$string['th:paymentmodified'] = 'Laatst gewijzigd';
$string['th:cost'] = 'Kosten';
$string['th:rawcost'] = 'Cursusprijs';
$string['title:couponedit'] = 'ClassicPay - Coupon bewerken';
$string['title:transactions'] = 'ClassicPay - Transacties';
$string['coupon:delete'] = 'Coupon verwijderen';
$string['coupon:delete:warn'] = '<p>Je staat op het punt de coupon met de volgende details te verwijderen.</p>
<p>Cursus: <i>{$a->course}</i><br/>Couponcode: <i>{$a->code}</i><br/>Geldigheid: <i>{$a->validfrom} - {$a->validto}</i></p>
<p>Weet je zeker dat je dit wilt doen?</p>';
$string['coupon:edit'] = 'Coupon bewerken';
$string['coupon:saved'] = 'Coupon successvol aangemaakt';
$string['coupon:updated'] = 'Coupon gegevens successvol bewerkt';
$string['coupon:new'] = 'Voeg nieuwe coupon toe';
$string['coupon:edit'] = 'Bewerk bestaande coupon';
$string['coupon:invalid'] = 'Ongeldige coupon code';
$string['coupon:expired'] = 'Coupon code is verlopen';
$string['coupon:deleted'] = 'Coupon successvol verwijderd';
$string['coupon:details'] = 'Coupon details';
$string['coupon:invoice'] = 'Factuur opnieuw aanvragen';
$string['entiresite'] = 'Gehele website / alle cursussen';
$string['couponcode'] = 'Couponcode';
$string['couponcodemissing'] = 'Couponcode moet ingegeven worden';
$string['validfrom'] = 'Geldig vanaf';
$string['validfrommissing'] = 'Startdatum moet ingegeven worden';
$string['validto'] = 'Geldig tot';
$string['validtomissing'] = 'Einddatum moet ingegeven worden';
$string['percentage'] = 'Percentage';
$string['percentagemissing'] = 'Percentage moet ingegeven worden';
$string['maxusage'] = 'Maximum aantal';
$string['maxusage_help'] = 'Maximum aantal keren dat de coupon code kan worden gebruikt.<br/>
Als je dit op 0 laat staan, betekent dit onbeperkt gebruik van de code.';
$string['coupon:newprice'] = 'Korting: {$a->percentage}<br/>Korting: {$a->currency} {$a->discount}<br/>Nieuwe prijs: <b>{$a->currency} {$a->newprice}</b>';
$string['checkcode'] = 'Controleer coupon code';
$string['paynlsettings_apply'] = '<a href="{$a}">Aanmelden voor PAYNL Merchant</a>';
$string['cp:coupons'] = 'Coupons beheren';
$string['cp:subscriptions'] = 'Cursusaanmeldingen';
$string['cp:paynlconnection'] = 'Classicpay Service Info';
$string['cp:transactions'] = 'Transacties';
$string['cp:apply'] = 'Merchant Aanmelding';
$string['cp:legal'] = 'Aansprakelijksheidsinformatie';
$string['couponcodeexists'] = 'Coupon code bestaat al';
$string['coupon:status:impending'] = 'INACTIEF';
$string['coupon:status:active'] = 'ACTIEF';
$string['coupon:status:expired'] = 'VERLOPEN';
$string['coupon:status:maxused'] = 'VERBRUIKT';
$string['coupons:backtooverview'] = 'Terug naar coupon overzicht';
$string['err:percentage-negative'] = 'Kortingspercentage kan niet negatief zijn';
$string['err:percentage-exceed'] = 'Kortingspercentage kan niet boven 100% zijn';
$string['validfromhigherthanvalidto'] = 'Geldigheid vanaf is na geldigheid tot';
$string['classicpay:config'] = 'Configureer classicpay';
$string['classicpay:createcoupon'] = 'Coupons aanmaken';
$string['classicpay:editcoupon'] = 'Coupons bewerken';
$string['classicpay:deletecoupon'] = 'Coupons verwijderen';
$string['classicpay:manage'] = 'Beheer classicpay';
$string['classicpay:unenrol'] = 'Unenrol gebruikers';
$string['classicpay:unenrolself'] = 'Unenrol zelf';
$string['gettokentime'] = 'Tijd op remote API: {$a}';
$string['paynlconn:remote:error'] = 'Fout tijdens aanroep remote PAY API: {$a}';
$string['apply:alreadyconfigured'] = 'Je hebt al een PAYNL Merchant token geconfigureerd.';
$string['api:notconfigured'] = 'Je hebt nog geen PAYNL Merchant token geconfigureerd.<br/>
Configureer aub je instellingen zodat een token aanwezig is of <a href="{$a}">meldt je hier aan</a>';
$string['paylogin'] = 'Inloggen bij PAYNL';
$string['registrationcancelled'] = 'Registratie geannuleerd. Je wordt nu doorverwezen naar de startpagina.';
$string['page:title:spapply'] = 'Aanmelden voor PAYNL Merchant';
$string['apply:email'] = 'Emailadres';
$string['apply:phone'] = 'Telefoonnummer';
$string['apply:phone2'] = 'Alternatief telefoonnummer';
$string['apply:firstname'] = 'Voornaam';
$string['apply:lastname'] = 'Achternaam';
$string['apply:companyname'] = 'Naam organisatie';
$string['apply:cocnumber'] = 'KVK nummer';
$string['apply:gender'] = 'Geslacht';
$string['apply:gender:male'] = 'Man';
$string['apply:gender:female'] = 'Vrouw';
$string['apply:street'] = 'Straatnaam';
$string['apply:housenumber'] = 'Huisnummer';
$string['apply:zipcode'] = 'Postcode';
$string['apply:city'] = 'Stad';
$string['apply:countrycode'] = 'Landcode';
$string['apply:bankaccountowner'] = 'Tenaamstelling bankrekening';
$string['apply:bankaccountnumber'] = 'Bankaccount Number (IBAN)';
$string['apply:bic'] = 'BIC / Swift';
$string['apply:vatnumber'] = 'BTW nummer';
$string['apply:languageid'] = 'Taal';
$string['apply:authorizedtosign'] = 'Geautoriseerd om te tekenen?';
$string['apply:authorizedtosign:no'] = 'Nee';
$string['apply:authorizedtosign:yes'] = 'Geautoriseerd om zelfstandig te tekenen';
$string['apply:authorizedtosign:shared'] = 'Gedeelde authorisatie om te tekenen';
$string['apply:ubo'] = 'UBO';
$string['apply:cocdocument'] = 'KVK uittreksel';
$string['apply:bankdocument'] = 'Kopie bankafschrift';
$string['apply:iddocument'] = 'ID document';
$string['apply:submit'] = 'Aanmelden';
$string['apply:sitename'] = 'Website naam';
$string['apply:siteurl'] = 'Website adres';
$string['apply:addsignee'] = 'Tekeningsbevoegde toevoegen';
$string['apply:bankname'] = 'Naam bank';
$string['apply:bankcity'] = 'Vestigingsplaats bank';
$string['apply:paymentprofile'] = 'Betalingsmethod(en)';
$string['apply:header:signees'] = 'Optionele extra tekeningsbevoegden voor aanmelding';
$string['apply:header:paymentprofiles'] = 'Beschikbare betalingsmethoden voor uw aanmelding';
$string['apply:header:details'] = 'Registratie informatie';
$string['apply:button:addsignee'] = 'Voeg {no} Tekeningsbevoegde(n) aan het formulier toe';
$string['apply:page:heading'] = 'Aanmelden voor een PAYNL Merchant token.';
$string['apply:nav'] = 'PAYNL Merchant aanmelding.';

$string['apply:email_help'] = 'Email adres. Deze is noodzakelijk voor ons om contact op te nemen met betrekking tot je aanmelding .';
$string['apply:phone_help'] = 'Telefoonnummer waar je bereikbaar bent. Dit is noodzakelijk om in contact te komen indien er problemen of inconsistenties zijn met betrekking tot je aanmelding.';
$string['apply:phone2_help'] = 'Alternatief telefoonnummer. Je mag deze leeg laten indien niet van toepassing';
$string['apply:firstname_help'] = 'Je voornaam';
$string['apply:lastname_help'] = 'Je achternaam';
$string['apply:companyname_help'] = 'De naam van de organisatie';
$string['apply:cocnumber_help'] = 'Je Kamer van Koophandel nummer';
$string['apply:gender_help'] = 'Je geslacht';
$string['apply:street_help'] = 'Straatnaam van de organisatie';
$string['apply:housenumber_help'] = 'Huisnummer van de organisatie';
$string['apply:zipcode_help'] = 'Postcode van de organisatie';
$string['apply:city_help'] = 'Vestigingsplaats van de organisatie';
$string['apply:countrycode_help'] = 'Land waar de organisatie is gevestigd';
$string['apply:bankaccountowner_help'] = 'Tenaamstelling van de bankrekening van de organisatie';
$string['apply:bankaccountnumber_help'] = 'Bankrekeningnummer. Dit moet een IBAN nummer zijn';
$string['apply:bic_help'] = 'BIC of SWIFT code';
$string['apply:vatnumber_help'] = 'BTW nummer van de organisatie, indien van toepassing';
$string['apply:languageid_help'] = 'Standaard taal van je account';
$string['apply:authorizedtosign_help'] = 'Dit veld geeft aan of je tekeningsbevoegd bent. Indien onbekend, laat dan deze optie op "NEE" staan';
$string['apply:ubo_help'] = 'UBO';
$string['apply:usecompanyauth_help'] = 'Gebruik organisatiesauthenticatie?';
$string['apply:sendemail_help'] = 'Geeft aan of je een registratiemail wilt ontvangen en in welke vorm.';
$string['apply:invoiceinterval_help'] = 'Hoe vaak dienen facturen / uitbetalingen verwerkt te worden?';
$string['apply:cocdocument_help'] = 'Uittreksel van je Kamer van Koophandel registratie. Dit document is noodzakelijk voor validatie en aansluiting.';
$string['apply:bankdocument_help'] = 'Meest recente kopie van je bankafschrift. Dit document is noodzakelijk voor validatie en aansluiting.<br/>
Alle bedragen mogen zonder meer onleesbaar worden gemaakt, zolang tenaamstelling en rekeningnummer maar zichtbaar zijn';
$string['apply:iddocument_help'] = 'Kopie van een ID document. Dit mag een kopie van een paspoort, ID kaart of rijbewijs zijn. Dit document is noodzakelijk voor validatie en aansluiting.';
$string['apply:settlebalance_help'] = '??';
$string['apply:sitename_help'] = 'Volledige site naam. We hebben dit nodig om je website / programma te registreren.';
$string['apply:siteurl_help'] = 'Volledige site URL. We hebben dit nodig om je website / programma te registreren.';
$string['apply:addsignee_help'] = 'Indien er meerdere tekeningsbevoegden zijn, vul deze dan aan. Let op: ELKE tekeningsbevoegde op het KVK uittreksel MOET hier worden opgegeven, anders kunnen wij uw aanmelding niet volledig verwerken.';
$string['apply:bankname_help'] = 'Naam van de bank, bijv. Rabobank, ING, ...';
$string['apply:bankcity_help'] = 'Vestigingsplaats van uw bank.';

$string['apply:success'] = '<div><p>Registratie is succesvol verzonden en verwerkt</p><p>{$a->info}</p>';
$string['apply:fail'] = '<div><p>Registratie mislukt:</p><p>{$a->errcode}: {$a->error}</p><p>{$a->info}</p></div>';

$string['task:process_pending_orders'] = 'Synchroniseert uitstaande betalingsstatussen voor het geval we exchange verzoeken gemist hebben';
$string['task:sync_cpplus'] = 'Synchroniseert of we een classicpay PLUS account hebben';
$string['task:request_invoices'] = 'Synchroniseert factuur verzoek queue';
$string['classicpay:plus:status:valid'] = 'Je hebt een classicpay plus account';
$string['classicpay:plus:status:invalid'] = 'Je hebt <i>geen</i> classicpay plus account.';
$string['classicpay:plus:status:error'] = 'Fout tijdens controleren classicpay plus account: {$a}';
$string['classicpay:plus:description'] = '<div class="enrol-classicpay-info">Wanneer je een Classicpay Plus account aanvraagt, betekent dit dat je je aanmeldt voor het genereren van facturen.<br/>
Dit brengt extra kosten met zich mee, maar heeft als voordeel dat zowel jij als de eindgebruiker van ons automatisch een factuur krijgt wanneer een cursus wordt aangekocht.<br/>
Door Classicpay Plus in te schakelen ga je automatisch akkoord met het in rekening brengen van 10 cent, bovenop de transactiekosten, per transactie.<br/>
Deze zullen door ons periodiek worden bepaald en je zult van ons, achteraf, een rekening krijgen voor het aantal gegenereerde facturen.<br/>
Het eerste moment van genereren van de factuur is daarbij leidend. Het hoeft dus niet zo te zijn dat het aantal transacties in zekere periode overeenkomt met het aantal in rekening gebrachte facturen.</div>';
$string['cppapply:enable'] = 'Classicpay Plus inschakelen';
$string['cppapply:disable'] = 'Classicpay Plus uitschakelen';

$string['cppoapply:header:paymentprofiles'] = 'Je geconfigureerde betalingsmethoden';
$string['cppoapply:paymentprofiles:simple'] = '<div class="enrol-classicpay-info">"Vrije" betalingsmethoden.<br/>
Vrije profielen zijn zonder aanleveren van extra informatie aan en uit te zetten.</div>';
$string['cppoapply:paymentprofiles:setting'] = '<div class="enrol-classicpay-info"><span style="color:red">*&nbsp;</span>De volgende betalingsmethoden vereisen dat je extra informatie aanlevert.<br/>
Indien je een van deze optie inschakelt, zul je van ons een bevestigingsemail krijgen waarin we je informeren dat wij contact met je opnemen.</div>';

$string['apply:cpp:success'] = 'Classic Pay plus account succesvol gewijzigd';
$string['apply:cpp:fail'] = 'Fout bij wijzigen Classic Pay plus account';
$string['apply:cpp:error'] = 'Fout bij wijzigen Classic Pay plus account: {$a}';
$string['cppapply:header'] = 'Classicpay Plus account status';
$string['button:cppo:update'] = 'Betalingsmethoden updaten';
$string['err:getserviceprofiles'] = 'Fout bij ophalen betalingsmethoden: {$a->error}';
$string['err:setserviceprofiles'] = 'Fout bij updaten betalingsmethoden: {$a->error}';
$string['setserviceprofiles:success'] = 'Betalingsmethoden succesvol bijgewerkt:<br/>{$a}';
$string['warn:servicepage'] = '<div class="enrol-classicpay-warn">Elke actie die je hier uitvoert zal op onze Classicpay service worden uitgevoerd.<br/>
Lees het commentaar bij elke mogelijkheid goed door en vergewis je ervan dat je het begrijpt voordat je doorgaat.</div>';
$string['apply:information'] = '<div class="enrol-classicpay-info"><strong>Belangrijke informatie voor aanmelding.</strong><br/>
Lees de volgende punten a.u.b. goed door voordat je doorgaat met de aanmelding.<br/>
<ul>
<li>Vergewis je ervan dat dit formulier naar waarheid en beste weten is ingevuld.</li>
<li>Controleer het formulier a.u.b. goed voor deze te verzenden.</li>
<li>Realiseer je dat <b>alle</b> documenten vereist zijn voor je aanmelding.</li>
<li>Elk document aanleveren via dit formulier zorgt ervoor dat je aanmelding sneller verloopt.</li>
<li>Je aanmelding vind gewoon plaats wanneer je de verplichte documenten niet meelevert.<br/>
Je kunt, bij succesvolle aanmelding, direct starten.<br/>
Echter, we zullen contact met je opnemen met betrekking to de vereiste documenten en je zult ze alsnog verplicht moeten aanleveren.<br/>
Bij het in gebreke blijven, of bij enig misbruik, zullen we je account afsluiten.</li>
<li>Elk misbruik zal aan de autoriteiten worden gemeld en kan leiden tot afsluiten of zelfs blacklisten van je account, zowel door ons als door de verstrekker van de betaalservice.</li>
<li>Elke indicatie van vervalsing van vereiste documenten, of moedwillig aanleveren van verkeerde gegevens zal aan de autoriteiten worden gemeld, zowel door ons als door de verstrekker van de betaalservice.</li>
<li><b><i>Uitbetalingen door de payment service provider zijn enkel ingeschakeld nadat alle documenten akkoord en bevestigd zijn.</i></b></li>
</ul></div>';
$string['event:payment:started'] = 'Classicpay betaling gestart';
$string['event:payment:complete'] = 'Classicpay betaling compleet';
$string['admin:page:legal'] = '<p>Classic Pay en Classic Pay Plus zijn ontwikkeld door Sebsoft.</p>
<p>Echter is Sebsoft <i>niet</i> aansprakelijk voor het verlopen van de transacties binnen dit systeem.<br/>
Alle aansprakelijkheid wordt bij voorbaat afgewezen.</p>
<p>U als merchant (of website eigenaar) gaat een contract aan met Pay.nl ten behoeve van het verkrijgen van de door u geïncasseerde gelden.<br/>
De uitbetaling wordt verzorgd door Stichting Derdengelden Pay.nl.<br/>
Sebsoft stuurt u een factuur voor alle transacties en/of de gemaakte facturen (van toepassing voor ClassicPay Plus).</p>';

$string['coupontype'] = 'Type';
$string['coupontype:percentage'] = 'Percentage';
$string['coupontype:value'] = 'Waarde';
$string['valuemissing'] = 'Er moet een waarde worden opgegeven';
$string['err:value-negative'] = 'Korting kan niet negatief zijn';
$string['th:type'] = 'Type';
$string['th:value'] = 'Waarde';
$string['invoice:requested'] = 'Je verzoek tot uitdraaien van een (nieuwe) factuur is verwerkt';
$string['apply:bic'] = 'BIC / Swift';
$string['apply:bic_help'] = 'BIC of SWIFT code';

$string['enablecoupon'] = 'Gebruik van coupons inschakelen?';
$string['enablecoupon_help'] = 'Vink dezeoptie aan als je standaard het invullen van coupon codes wilt inschakelen in het betaalscherm.
Je kunt dit per enrolment instantie aan of uitschakelen.';

$string['privacy:metadata:enrol_classicpay'] = 'De classicpay aanmeldplugin verzend gebruikersgegevens naar de website van PayNL.';
$string['privacy:metadata:enrol_classicpay:userid'] = 'GebruikersID';
$string['privacy:metadata:enrol_classicpay:courseid'] = 'Cursus ID';
$string['privacy:metadata:enrol_classicpay:instanceid'] = 'Aanmeld gegevensrij ID';
$string['privacy:metadata:enrol_classicpay:orderid'] = 'Referentie naar bestelID';
$string['privacy:metadata:enrol_classicpay:status'] = 'Statuscode van bestelling';
$string['privacy:metadata:enrol_classicpay:statusname'] = 'Statusnaam van bestelling';
$string['privacy:metadata:enrol_classicpay:gateway_transaction_id'] = 'Transactie ID bij PayNL';
$string['privacy:metadata:enrol_classicpay:gateway'] = 'Gebruikte gateway';
$string['privacy:metadata:enrol_classicpay:rawcost'] = 'Bruto kosten van de bestelling';
$string['privacy:metadata:enrol_classicpay:cost'] = 'Totale kosten van bestelling';
$string['privacy:metadata:enrol_classicpay:percentage'] = 'Berekend kortingspercentage op bestelling';
$string['privacy:metadata:enrol_classicpay:discount'] = 'Berekende korting op bestelling';
$string['privacy:metadata:enrol_classicpay:hasinvoice'] = 'Heeft bestelling een bijbehorende factuur?';
$string['privacy:metadata:enrol_classicpay:timecreated'] = 'Tijdstip van aanmaken van bestelling';
$string['privacy:metadata:enrol_classicpay:timemodified'] = 'Tijdstip van laatste wijziging voor bestelling';

$string['costerror'] = 'Fout: De prijs moet een cijfer zijn';
$string['unenrolselfconfirm'] = 'Weet je zeker dat je jezelf wilt afmelden van {$a}?';
