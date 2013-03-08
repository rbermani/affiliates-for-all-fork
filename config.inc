<?php

// This parameter is used in affiliate links, and contains the affiliate
// number.  For example, a link might look like http://...?ref=123 for
// affiliate 123.

$affiliate_referrer_parameter = 'ref';

// This parameter is also used in affiliate links, and carries the affiliate's
// own data.  For example, it might contain the user's search keywords, to
// allow for optimisation of a PPC campaign.

$affiliate_data_parameter = 'data';

// The name of the cookie which stores the referring affiliate:

$affiliate_cookie = 'affiliate';

// The lifetime of the cookie:

$cookie_lifetime = 30 /* days */;

// The hostname for which the affiliate cookie should be set.  You should leave
// this unchanged unless your checkout is on a different domain to your store.
// For example, if you have http://www.example.com for your store and
// https://secure.example.com for your checkout, you should set
// $cookie_domain to example.com.
//
// Note that AfA will not work at all if the store and cart are on completely
// different domains.  For example, you might have http://www.example.com for
// your store, and https://example.mysecureserver.com for your checkout.  This
// configuration will not work.

$cookie_domain = '';

// The currency symbol and SWIFT code:

$currency = '$';
$currency_code = 'USD';

// This should be set to the base of your Magento installation:

$store_home = '';

// You can give a fixed amount of commission per order, or you can give a
// percentage.  If you give both, they add.  Set these variables to the
// percentage rate and the fixed amount which you want to pay to your
// affiliates:

$commission_percent = 0;
$commission_fixed = '5.00';
$commission_parent_percent = 0;
$commission_parent_fixed = '2.00';

// Set this to the name of your affiliate programme.  It will be shown at the
// top of all the screens used by affiliates:

$affiliate_programme_name = 'Zeno Books - Affiliates';

// If you want affiliates to share revenue for all the orders their customers
// place, set the following to TRUE.  If you only want them to receive revenue
// share when customers have an affiliate cookie set on their browsers, set
// it to FALSE.
//
// For example, suppose an affiliate introduces customer X.  X places an order
// and the affiliate receives commission.  After that, X changes browser,
// meaning that he no longer has an affiliate cookie set.  He then places
// another order.  If this variable is TRUE, the affiliate receives commission
// on this order too (provided X uses the same account of course).  If this
// variable is FALSE, he does not.

$lifetime_revenue_share = FALSE;


// If you want to be notified when AfA accepts an order, or marks an order
// shipped, enter an email address here:

$notification_email_address = '';

// If you don't want these notifications, leave the email address out, by
// changing the above line to read:
// $notification_email_address = '';


// The following address is used as the sender for these email notifications,
// and also for email notifications sent to affiliates:

$administrator_email_address = 'sales@zenobooks.com';

// You *must* change this to an email address which you control, as
// you will not be considered a legitimate mail sender for
// affiliatesforall.org.  As a result many mail servers would consider your
// notifications to be spam.


// Affiliates can be given full or restricted information about the referred
// customers and their orders.  The fields you want to reveal should be placed
// in this list:

$order_fields_available =
    'id, status, parent, customer_id, customer_name, total, ' .
    'commission, date_entered, affiliate_data';
$order_fields_headings =
    'Order Number, Status, Second Tier, Cust ID, Cust Name, Total, ' .
    'Commission, Order Date, Campaign Data';

// The following secret is used to secure communications between the shopping
// cart and the affiliate system.  Just choose something that will be hard to
// guess.

$rpc_secret = 'Affiliate7932';

// Fill in the settings for your database below.  Note that all testing has
// been carried out on MySQL 5.0.

$database_dsn = 'mysql:dbname=bobberma_affiliates;host=127.0.0.1';
$database_username = '';
$database_password = '';

// This is the name of the cookie which is used for AfA sessions.  (It is set
// for affiliates and administrators, but not customers of your web shop.)
// If you run two instances of AfA on the same domain, you should change it
// for at least one of them, as otherwise they could interfere with each other.

$session_cookie_name = 'AfASESSID';

// The timezone where AfA is running:

date_default_timezone_set('Europe/London');


// AES Secret key string used for MySQL AES_ENCRYPT()
$aes_key = "Secret7";

// reCAPTCHA API URL
$recaptcha_url = "http://api-verify.recaptcha.net/verify";

// The public & private keys used for reCAPTCHA
$publickey = "6LfIHwwAAAAAAEXak6bZ3Bp-7u92jrOMhzjV462t";
$privatekey = "6LfIHwwAAAAAAC_Z4wn7IpNJO0PXldCZozEX_bH5";

// return policy length (in days)
// Affiliates will not be paid for an item until this duration has passed
// after an order has been placed
$return_days = 25;

// Affiliates will be asked to agree to the following terms of business:

$terms_of_business = <<<end
<h1>Affiliate Program Service Agreement</h1>

<p>This Affiliate Program Service Agreement ("Services Agreement") sets forth
the terms and conditions of your use of ZenoBooks Affiliate Program ("Affiliate
Program") and serves to supplement the Registration Agreement ("Agreement")
between you on the one hand and ZenoBooks, LLC ("ZenoBooks") on the other. In
this Agreement "you" and "your" refer to you or any agent, employee, servant or
person authorized to act on your behalf, and the registrant listed in the WHOIS
contact information for the domain name. "We", "us" and "our" refer to
ZenoBooks, LLC ("ZenoBooks").</p>

<p>This Services Agreement explains our obligations to you, and explains your
obligations to us for the Affiliate Program offered by ZenoBooks. When you use
your account or permit someone else to use it to purchase or otherwise acquire
access to additional ZenoBooks service(s) or products or to cancel your
ZenoBooks service(s) (even if we were not notified of such authorization), this
Services Agreement covers such service or actions.</p>

<p>To be an authorized affiliate of ZenoBooks, you agree to abide by the terms
and conditions contained in this agreement.</p>

<p>Please read this agreement carefully before registering and using the
ZenoBooks service as an affiliate. By signing up for the ZenoBooks affiliate
program, you indicate your acceptance of this agreement and its terms and
conditions.</p>

<p>This Agreement contains the complete terms and conditions that apply to your
participation as a member of the ZenoBooks.com Affiliate Program.</p>

<p>THIS IS A LEGAL AGREEMENT BETWEEN YOU AND ZENOBOOKS. YOU CANNOT BECOME A
MEMBER OF THE AFFILIATE PROGRAM UNLESS YOU HAVE ACCEPTED EACH AND EVERY TERM
HEREOF.</p>

<p></p>

<p>1. DESCRIPTION OF THE AFFILIATE PROGRAM</p>

<p>To enroll in the Affiliate Program, you will submit a completed Program
Application through our website. Your account will be instantly active in our
program. We will evaluate your account in good faith to ensure you comply with
all our rules and agreements. We may reject your account if we determine that
your site is unsuitable for the Program for any reason, including, but not
limited to, if your site incorporates images or content that is unlawful,
defamatory, obscene, harassing or otherwise objectionable, such as sites that
facilitate illegal activity or promote violence or promote or assist others in
promoting copyright infringement (collectively, "Content Restrictions").
Acceptance into the Affiliate Program is at our sole discretion, which may be
withheld for any reason or for no reason at all.</p>

<p>ZenoBooks Affiliate Program allows you to provide a platform for advertising
ZenoBooks via your "web site" or "publishing location" (such as an electronic
newsletter, blog or flyer), thereby driving traffic to ZenoBooks web site or
web site content, whereby you may earn a portion of the revenue generated
("commission") if a person or entity that is not you ("visitor") makes a
purchase of a ZenoBooks product or service after being referred to the
ZenoBooks web site from an internet hyperlink ("link") contained on your web
site or publishing location.</p>

<p>After being referred to the ZenoBooks web site from a link contained on your
web site or publishing location, visitors have fifteen (15) days to complete
their purchase in order for you to receive a commission. Should the visitor
click on another web site or publishing location&rsquo;s link that is not
controlled by you, or return to the ZenoBooks web site via another source-coded
advertising link during the initial fifteen (15) days, your link will be
overwritten and you will not receive a commission for that visitor&rsquo;s
purchase. All ZenoBooks products and services will be eligible for a
commission.</p>

<p>You acknowledge and agree that only first purchases completed by visitor
through ZenoBooks web site will be eligible for a commission.</p>

<p>2. PARTICIPATION IN THE AFFILIATE PROGRAM</p>

<p>You are not eligible for a commission payment on purchases made by you, your
employees, agents, representatives, or contractors, as well as made from
several accounts owned by the same person as far as commission is paid for the
first purchase only made by a certain person.</p>

<p>3. WEB SITE AND PUBLISHING LOCATION RESTRICTIONS</p>

<p>You acknowledge and agree that your web site or publishing location shall
not (and shall not enable others to):</p>
<ul>
  <li>Contain false, inaccurate or misleading information about ZenoBooks
    products and services;</li>
  <li>Be a ZenoBooks look-a-like site that gives an impression that it is
    affiliated with ZenoBooks.com;</li>
  <li>Display, promote or sell illegal content, or offer any illegal product or
    service;</li>
  <li>Engage in sending unsolicited commercial email ("spam") or indiscriminate
    advertising;</li>
  <li>Contain pornographic or obscene content, tasteless images, or excessively
    violent or hate-related material, as determined by ZenoBooks in its sole
    discretion;</li>
  <li>Violate any applicable law;</li>
  <li>Advocate, promote, or encourage violence or discrimination against any
    person, organization, or governmental entity;</li>
  <li>Contain links to web sites containing any of the aforementioned
  content;</li>
  <li>Cause any purchases to be made that are not in good faith (such as using
    any device, program, robot iframes, or hidden frames, etc.).</li>
</ul>

<p>In addition, you acknowledge and agree that you shall not:</p>
<ul>
  <li>Copy, alter or modify any icons, buttons, banners, graphics, files, or
    content contained in ZenoBooks links, including but not limited to removing
    or altering any copyright or trademark notices. You recognize that
    ZenoBooks owns all rights in and to all information regarding the visitors
    that you refer to ZenoBooks web site;</li>
  <li>Alter the tracking codes to allow you to collect personally identifiable
    information of visitors that would allow you to personally identify
    visitors;</li>
  <li>Engage in activities that ZenoBooks determines, in its sole discretion,
    to be harmful to its customers, employees, operations, or reputation;</li>
</ul>

<p>Subject to the terms and conditions hereof, you are granted a limited,
non-exclusive, non-transferable license to access and download such Links and
other designated promotional materials for placement on your Affiliate Site for
the sole and exclusive purpose of promoting websites owned, operated or
controlled by ZenoBooks. You acknowledge and agree that ZenoBooks may terminate
you from the Program and you will forfeit any monies otherwise due you
hereunder if you, your web site or your publishing location violates, as
determined by ZenoBooks in its sole discretion, any of the aforementioned
restrictions or additional restrictions.</p>

<p>4. COMMISSIONS</p>
<ul>
  <li>We will pay you a flat $5.00 commission on any first sale completed by
    visitors whom you referred. We will pay you a flat $2.00 commission on any
    sale generated by another Affiliate (friend-affiliate) whom you referred to
    the Affiliate Program. In order to collect the $2.00 friend affiliate
    commission, the friend-affiliate must put your email address as the
    referring email address. We cannot pay friend-affiliate commissions if the
    referral email address does not match. Commissions due and owing to you
    under the Program will be paid to you directly by ZenoBooks.com one time
    per month: on the 15th day of the month. Payments will be in the form of
    PayPal or a ZenoBooks check payable to you as identified in your
    application, and will be mailed to the street address indicated in your
    application. There will be a 45 day hold from the time you generate a sale,
    and the time you are eligible to be paid on that sale. If during the 45 day
    hold the Referral takes advantage of our 30 day money back guarantee your
    commission will be forfeited. After this time, commission will be paid out
    to either you or your PayPal account(for PayPal there is a minimum
    requirement currently set to $100 (US dollars).</li>
  <li>Commissions will be debited or withheld on any orders refunded for fraud
    or charged back by the issuing bank.</li>
  <li>ZenoBooks will track all commissions earned and may, at its sole
    discretion, decide not to pay any commission to you should we believe that
    any referral has been made in violation of its guidelines, due to
    visitor/customer fraud, or due to visitor/customer contract cancellation.
    It is your sole and absolute duty to follow precisely this Agreement and
    its guidelines at all times. ZenoBooks is under no obligation whatsoever to
    pay any commission to any affiliate who does not strictly follow this
    Agreement and its guidelines, as modified from time to time.</li>
</ul>

<p></p>

<p>5. YOUR RESPONSIBILITY</p>

<p>As an affiliate, we provide you with the links and other materials necessary
to promote ZenoBooks products and services, which you may display in any area
of your web site or publishing location as you wish. The links will identify
your site as a member of our Affiliate Program and will establish a link from
your web site or publishing location to ours. You may promote the products and
services that ZenoBooks offers in any manner you choose unless it misleads
visitors about ZenoBooks services. .</p>

<p>You will be solely responsible for the development, operation and
maintenance of your site and for all materials that appear on your site. We
shall have no responsibility for the development, operation and maintenance of
your site and for all materials that appear on your site. You shall also be
responsible for ensuring that materials posted on your site do not violate or
infringe upon any laws, including but not limited to 18 U.S.C. Section 2257, or
the rights of any third party (including, for example, copyrights, trademarks,
privacy, or other personal or proprietary rights), and ensuring that materials
posted on your site are not libelous or otherwise illegal. You must have
express permission to use another party's copyrighted or other proprietary
material. We will not be responsible if you use another party's copyrighted or
other proprietary material in violation of the law. In addition to the
foregoing, we will immediately terminate your participation in the Program if
we believe you have engaged in any of the following:</p>

<p>- Unsolicited mass e-mail solicitations, IRC postings or any other form of
spamming, including but not limited to, newsgroups or AOL customers or
otherwise violate the anti-spamming policies of ZENOBOOKS or state law;</p>

<p>- Provide inaccurate or incomplete information to ZENOBOOKS concerning your
identity, address or other required information;</p>

<p>- Attempt to cheat, defraud or mislead us in any way;</p>

<p>- Misrepresent to the public the terms and conditions of our sites or your
sites;</p>

<p>6. OUR RESPONSIBILITY</p>

<p>ZenoBooks is responsible for payment processing, cancellations, returns and
other related customer service for ZenoBooks products and services. ZenoBooks
is also responsible for tracking affiliate sales and commissions and providing
this information to the affiliate via activity reporting tool available within
ZenoBooks account.</p>

<p>7. TERM OF THE AGREEMENTS</p>

<p>The term of this Agreement will begin upon our acceptance of your Affiliate
Program Application and will end when terminated by either party. Either you or
we may terminate this Agreement at any time, with or without cause, by giving
the other party notice of termination. Notice by e-mail, to your e-mail address
on our records, is considered sufficient notice for to terminate this
Agreement. If this Agreement is terminated because you have violated the terms
of this Agreement you are not eligible to receive any commissions payments,
even for commissions earned prior to the date of termination. If this Agreement
is terminated for any other reason, you are only eligible to earn a commission
on sales occurring during the term of the Agreement, and commissions earned
through the date of termination will remain payable only if the related orders
are not canceled or returned. We reserve the right to withhold your final
payment for a reasonable time to ensure that the correct amount is paid.</p>

<p>8. MODIFACATION</p>

<p>We may modify any of the terms and conditions and commission payout rates
contained in this Agreement, at any time and in our sole discretion. Notice of
any change by e-mail, to your address on our records, or the posting on our
site of a change notice of a new agreement, is considered sufficient notice for
notifying you of a modification to the terms and conditions of this Agreement.
Modifications may include, but are not limited to, changes in the scope of
available commission fees, commission schedules, payment procedures, and
Affiliate Program rules. All such modifications shall take effect 48 hours
after we serve notice as provided above, unless we indicate otherwise. If any
modification is unacceptable to you, your only recourse is to terminate this
Agreement. Your continued participation in the Affiliate Program, following our
posting of a change notice or new agreement on our site, will constitute
binding acceptance of the change.</p>

<p>9. RELATIONSHIP OF PARTIES</p>

<p>You and ZenoBooks are independent contractors, and nothing in this Agreement
will create any partnership, joint venture, agency franchise, sales
representative, or employment relationship between the parties. You will have
no authority to make or accept any offers or representations on our behalf. You
will not make any statement, whether on your site or otherwise, that reasonably
would contradict anything in this Section. You are not an agent of the
ZenoBooks and ZenoBooks expressly disclaims responsibility for any conduct by
you in violation of our terms of agreement.</p>

<p>10. LIMITATION OF LIABILITY</p>

<p>UNDER NO CIRCUMSTANCES SHALL WE BE LIABLE FOR ANY DIRECT, INDIRECT,
INCIDENTAL, PUNITIVE, SPECIAL, OR CONSEQUENTIAL DAMAGES FOR ANY REASON
WHATSOEVER RELATED TO THIS AGREEMENT, YOUR USE OR INABILITY TO USE OUR WEB
SITE(S) OR THE MATERIALS AND CONTENT OF THE WEB SITE(S) OR ANY OTHER WEB SITES
LINKED TO SUCH WEB SITE(S) OR YOUR PROVISION OF ANY PERSONALLY IDENTIFIABLE
INFORMATION TO BACKEND SERVICE PROVIDER OR ANY THIRD PARTY. THIS LIMITATION
APPLIES REGARDLESS OF WHETHER THE ALLEGED LIABILITY IS BASED ON CONTRACT, TORT,
WARRANTY, NEGLIGENCE, STRICT LIABILITY OR ANY OTHER BASIS, EVEN IF WE HAVE BEEN
ADVISED OF THE POSSIBILITY OF SUCH DAMAGES OR SUCH DAMAGES WERE REASONABLY
FORESEEABLE. BECAUSE CERTAIN JURISDICTIONS DO NOT PERMIT THE LIMITATION OR
ELIMINATION OF LIABILITY FOR CONSEQUENTIAL OR INCIDENTAL DAMAGES, OUR LIABILITY
IN SUCH JURISDICTIONS ARISING WITH RESPECT TO THIS AGREEMENT AND THE AFFILIATE
PROGRAM, SHALL BE LIMITED TO THE TOTAL COMMISSIONS PAID OR PAYABLE TO YOU UNDER
THIS AGREEMENT OR TO THE GREATEST EXTENT PERMITTED BY LAW. IF ANY PROVISION OF
THIS LIMITATION OF LIABILITY IS FOUND TO BE UNENFORCEABLE, ONLY SUCH PROVISION
SHALL BE REMOVED AND THE REMAINDER SHALL BE ENFORCED TO THE GREATEST EXTENT
PERMITTED BY LAW.</p>

<p>11. INDEMNITY AND DEFENSE</p>

<p>With respect to ZenoBooks, as well as the contractors, agents, employees,
officers, directors, shareholders, and affiliates of such parties, you agree to
defend, release, indemnify, and hold such parties harmless from all
liabilities, claims and expenses, including attorney&rsquo;s fees and court
costs, for third party claims relating to or arising under the Agreements, the
Service(s) provided hereunder, or your use of the Service(s), including,
without limitation, infringement by you, or by anyone else using the Service(s)
we provide to you, of any intellectual property or other proprietary right of
any person or entity, or from the violation of any of our operating rules or
policies relating to the Service(s) provided. When we may be involved in a suit
involving a third party and which is related to our Service(s) to you under
these Agreements, we may seek written assurances from you in which you promise
to defend, indemnify and hold us harmless from the costs and liabilities
described in this paragraph. Such written assurances may include, in our sole
discretion, the posting of a performance bond(s) or other guarantees reasonably
calculated to guarantee payment. Your failure to provide such assurances may be
considered by us to be a breach of these Agreements by you. The terms of this
paragraph will survive any termination or cancellation of the Agreements.</p>

<p>12. REPRESENTATIONS AND WARRANTIES</p>

<p>You hereby represent and warrant to us that this Agreement has been duly and
validly executed and delivered by you and constitutes your legal, valid and
binding obligation, enforceable against you in accordance with its terms; and
that the execution, delivery and performance by you of this Agreement are
within your legal capacity and power; have been duly authorized by all
requisite action on your part; require the approval or consent of no other
persons; and neither violate nor constitute a default under the (i) provision
of any law, rule, regulation, order, judgment or decree to which you are
subject or which is binding upon you, or (ii) the terms of any other agreement,
document or instrument applicable to you or binding upon you. Should any law
enforcement agency or internet service provider provide ZenoBooks with notice
that you have engaged in transmission of unsolicited bulk e-mails or have
otherwise engaged in unlawful conduct or conduct in violation of said service
provider's terms of service, we reserve the right to cooperate in any
investigation relating to your activities including disclosure of your account
information in connection therewith.</p>

<p>13. CONFIDENTIALITY</p>

<p>We may disclose to you certain information as a result of your participation
as part of the Program, which information we consider to be confidential
(herein referred to as "Confidential Information"). For purpose of this
Agreement, the term "Confidential Information" shall include, but not be
limited to, any modifications to the terms and provisions of this Affiliate
Program Agreement made specifically for your site and not generally available
to other members of the Affiliate Program, website, business and financial
information relating to ZenoBooks, customer and vendor lists relating to
ZenoBooks and any members of the Affiliate Program, other than you.
Confidential Information shall also include any information that we designate
as confidential during the term of this Agreement. You agree not to disclose
any Confidential Information and that such Confidential Information shall also
include any information that we designate as confidential during the term of
this Agreement. You agree not to disclose any Confidential Information and that
such Confidential Information shall remain strictly confidential and secret and
shall not be utilized, directly or indirectly, by you for your own business
purposes or for any other purpose except and solely to the extent that any such
information is generally known or available to the public or if same is
required by law or legal process. We make no warranty, expressed or implied,
with respect to any information delivered hereunder, including implied
warranties of merchantability, fitness for a particular purpose or freedom from
patent, trademark or copyright infringements, whether arising by law, custom or
conduct, or as to the accuracy or completeness of the information and we shall
not have any liability to you or to any other person resulting from your or
such third person's use of the information.</p>

<p>14. MISCELLANEOUS</p>

<p>Terminated accounts cannot later apply to the Program without our express
written consent. This Agreement will be governed by the laws of the United
States, without reference to rules governing choice of laws. You may not assign
this Agreement, by operation of law or otherwise, without our prior written
consent. Subject to that restriction, this Agreement will be binding on, inure
to the benefit of, and enforceable against the parties and their respective
successors and assigns. Our failure to enforce your strict performance of any
provision of this Agreement will not constitute a waiver of our right to
subsequently enforce such provision or any other provision of this
Agreement.</p>

<p>15. You are not permitted to self refer yourself. For instance, if you join
our affiliate program and use the link code to signup for your own personal
gain, you will not receive your affiliate commission.</p>

<p></p>

<p>16. LEGAL AGE.</p>

<p>You attest that you are of legal age to enter into this Services
Agreement.</p>

<p>17. FINAL AGREEMENT.</p>

<p>This Services Agreement, the referenced agreements, together with all
modifications, constitute the complete and exclusive agreement between you and
us, and supersede and govern all prior proposals, agreements, or other
communications. This Services Agreement may not be amended or modified by you
except by means of a written document signed by both you and an authorized
representative of us.</p>

<p>18. NO AGENCY RELATIONSHIP.</p>

<p>Nothing contained in this Services Agreement shall be construed as creating
any agency, partnership, or other form of joint enterprise between the parties
hereto. Each party shall ensure that the foregoing persons shall not represent
to the contrary, either expressly, implicitly, by appearance or otherwise.</p>

<p>19. WAIVER.</p>

<p>The failure of us to require your performance of any provision hereof shall
not affect the full right to require such performance at any time thereafter;
nor shall the waiver by us of a breach of any provision hereof be taken or held
to be a waiver of the provision itself.</p>

<p>20. ENFORCEABILITY.</p>

<p>In the event that any provision of this Services Agreement shall be
unenforceable or invalid under any applicable law or be so held by applicable
court decision, such unenforceability or invalidity shall not render this
Services Agreement unenforceable or invalid as a whole. We will amend or
replace such provision with one that is valid and enforceable and which
achieves, to the extent possible, our original objectives and intent as
reflected in the original provision.</p>

<p>21. ASSIGNMENT AND RESALE.</p>

<p>Except as otherwise set forth herein, your rights under this Services
Agreement are not assignable or transferable. Any attempt by your creditors to
obtain an interest in your rights under this Services Agreement, whether by
attachment, levy, garnishment or otherwise, renders this Services Agreement
voidable at our option.</p>

<p>22. FORCE MAJEURE.</p>

<p>Neither party shall be deemed in default hereunder, nor shall it hold the
other party responsible for, any cessation, interruption or delay in the
performance of its obligations hereunder due to causes beyond its control
including, but not limited to: earthquake; flood; fire; storm; natural
disaster; act of God; war; terrorism; armed conflict; labor strike; lockout;
boycott; supplier failures, shortages, breaches, or delays; or any law, order
regulation, direction, action or request of the government, including any
federal, state and local governments having or claiming jurisdiction over
ZenoBooks, or of any department, agency, commission, bureau, corporation or
other instrumentality of any federal, state, or local government, or of any
civil or military authority; or any other cause or circumstance, whether of a
similar or dissimilar nature to the foregoing, beyond the reasonable control of
the affected party, provided that the party relying upon this section (i) shall
have given the other party written notice thereof promptly and, in any event,
within five (5) days of discovery thereof and (ii) shall take all steps
reasonably necessary under the circumstances to mitigate the effects of the
force majeure event upon which such notice is based; provided further, that in
the event a force majeure event described in this Section extends for a period
in excess of thirty (30) days in the aggregate, ZenoBooks may immediately
terminate this Services Agreement.</p>

<p>23. HEADINGS.</p>

<p>The section headings appearing in this Services Agreement are inserted only
as a matter of convenience and in no way define, limit, construe or describe
the scope or extent of such section or in any way affect such section.</p>
end;
