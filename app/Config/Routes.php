<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);


/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// CORS preflight: browsers send OPTIONS before cross-origin POST/PUT/etc.
// requests. Routing runs before filters in this CI version, so an OPTIONS
// request with no matching route 404s before App\Filters\Cors ever gets a
// chance to run — this catch-all answers every OPTIONS request with an
// empty 204; the actual Access-Control-* headers are added by that filter.
$routes->options('(:any)', static function () {
	return service('response')->setStatusCode(204);
});


 // License activation - no auth or license filter
$routes->get('activate', 'License::activate');
$routes->post('activate/process', 'License::process');

// INIT APP route
$routes->get('initapp', 'Api::initapp');

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//admin users
$routes->get('login', 'Login::index', ["filter" => "noauth"]);
$routes->post('authenticate', 'Login::authenticate');
$routes->get('forgot-password', 'Login::forgotPassword', ['filter' => 'noauth']);
$routes->post('forgot-password', 'Login::sendAdminResetEmail');
$routes->get('admin-reset/(:any)', 'Login::adminResetForm/$1');
$routes->post('admin-change-password', 'Login::adminChangePassword');
// Public church website (landing page)
$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('dashboard', 'Home::index', ['filter' => 'auth']);
$routes->get('logout', 'Login::logout');

// Website content management (admin)
$routes->get('landingContent', 'LandingContent::index', ['filter' => 'auth']);
$routes->post('updateLandingContent', 'LandingContent::update', ['filter' => 'auth']);

$routes->get('serviceTimesListing', 'LandingContent::serviceTimes', ['filter' => 'auth']);
$routes->get('newServiceTime', 'LandingContent::newServiceTime', ['filter' => 'auth']);
$routes->post('saveNewServiceTime', 'LandingContent::saveNewServiceTime', ['filter' => 'auth']);
$routes->get('editServiceTime/(:num)', 'LandingContent::editServiceTime/$1', ['filter' => 'auth']);
$routes->post('editServiceTimeData', 'LandingContent::editServiceTimeData', ['filter' => 'auth']);
$routes->get('deleteServiceTime/(:num)', 'LandingContent::deleteServiceTime/$1', ['filter' => 'auth']);

$routes->get('leadershipListing', 'LandingContent::leadership', ['filter' => 'auth']);
$routes->get('newLeader', 'LandingContent::newLeader', ['filter' => 'auth']);
$routes->post('saveNewLeader', 'LandingContent::saveNewLeader', ['filter' => 'auth']);
$routes->get('editLeader/(:num)', 'LandingContent::editLeader/$1', ['filter' => 'auth']);
$routes->post('editLeaderData', 'LandingContent::editLeaderData', ['filter' => 'auth']);
$routes->get('deleteLeader/(:num)', 'LandingContent::deleteLeader/$1', ['filter' => 'auth']);

$routes->get('signupRequests', 'LandingContent::signupRequests', ['filter' => 'auth']);
$routes->get('getSignupRequests', 'LandingContent::getSignupRequests', ['filter' => 'auth']);
$routes->get('approveSignupRequest/(:num)', 'LandingContent::approveSignupRequest/$1', ['filter' => 'auth']);
$routes->get('rejectSignupRequest/(:num)', 'LandingContent::rejectSignupRequest/$1', ['filter' => 'auth']);

$routes->get('membershipFormListing', 'LandingContent::membershipForm', ['filter' => 'auth']);
$routes->get('newMembershipField', 'LandingContent::newMembershipField', ['filter' => 'auth']);
$routes->post('saveNewMembershipField', 'LandingContent::saveNewMembershipField', ['filter' => 'auth']);
$routes->get('editMembershipField/(:num)', 'LandingContent::editMembershipField/$1', ['filter' => 'auth']);
$routes->post('editMembershipFieldData', 'LandingContent::editMembershipFieldData', ['filter' => 'auth']);
$routes->get('deleteMembershipField/(:num)', 'LandingContent::deleteMembershipField/$1', ['filter' => 'auth']);
$routes->get('moveMembershipFieldUp/(:num)', 'LandingContent::moveMembershipFieldUp/$1', ['filter' => 'auth']);
$routes->get('moveMembershipFieldDown/(:num)', 'LandingContent::moveMembershipFieldDown/$1', ['filter' => 'auth']);

// JSON API for the Next.js frontend (public, CORS-enabled)
$routes->get('api/landingContent', 'LandingApi::landingContent');
$routes->get('api/membershipForm', 'LandingApi::membershipForm');
$routes->post('api/joinChurch', 'LandingApi::join');
$routes->post('api/contactUs', 'LandingApi::contactUs');
$routes->options('api/landingContent', 'LandingApi::preflight');
$routes->options('api/membershipForm', 'LandingApi::preflight');
$routes->options('api/joinChurch', 'LandingApi::preflight');
$routes->options('api/contactUs', 'LandingApi::preflight');

// Contact messages (admin)
$routes->get('contactMessages', 'LandingContent::contactMessages', ['filter' => 'auth']);
$routes->post('getContactMessages', 'LandingContent::getContactMessages', ['filter' => 'auth']);
$routes->get('viewContactMessage/(:num)', 'LandingContent::viewContactMessage/$1', ['filter' => 'auth']);
$routes->post('replyContactMessage', 'LandingContent::replyContactMessage', ['filter' => 'auth']);
$routes->get('deleteContactMessage/(:num)', 'LandingContent::deleteContactMessage/$1', ['filter' => 'auth']);

//audios
$routes->get('audios', 'Audios::index', ['filter' => 'auth']);
$routes->post('fetchAudios', 'Audios::fetch', ['filter' => 'auth']);
$routes->get('newaudio', 'Audios::newAudio', ['filter' => 'auth']);
$routes->post('saveNewAudio', 'Audios::saveNewAudio', ['filter' => 'auth']);
$routes->get('editAudio/(:any)', 'Audios::editAudio/$1', ['filter' => 'auth']);
$routes->post('editAudioData', 'Audios::editAudioData', ['filter' => 'auth']);
$routes->get('deleteAudio/(:any)', 'Audios::deleteAudio/$1', ['filter' => 'auth']);

//branches
$routes->get('branchesListing', 'Branches::index', ['filter' => 'auth']);
$routes->get('newBranch', 'Branches::newBranch', ['filter' => 'auth']);
$routes->post('savenewbranch', 'Branches::savenewbranch', ['filter' => 'auth']);
$routes->get('editBranch/(:any)', 'Branches::editBranch/$1', ['filter' => 'auth']);
$routes->post('editBranchData', 'Branches::editBranchData', ['filter' => 'auth']);
$routes->get('deleteBranch/(:any)', 'Branches::deleteBranch/$1', ['filter' => 'auth']);

//video
$routes->get('videos', 'Videos::index', ['filter' => 'auth']);
$routes->post('fetchVideos', 'Videos::fetch', ['filter' => 'auth']);
$routes->get('newVideo', 'Videos::newVideo', ['filter' => 'auth']);
$routes->post('saveNewVideo', 'Videos::saveNewVideo', ['filter' => 'auth']);
$routes->get('editVideo/(:any)', 'Videos::editVideo/$1', ['filter' => 'auth']);
$routes->post('editVideoData', 'Videos::editVideoData', ['filter' => 'auth']);
$routes->get('deleteVideo/(:num)', 'Videos::deleteVideo/$1', ['filter' => 'auth']);

//livestream
$routes->get('livestreams', 'Livestream::index', ['filter' => 'auth']);
$routes->get('newLivestream', 'Livestream::newLivestream', ['filter' => 'auth']);
$routes->post('savenewlivestream', 'Livestream::savenewlivestream', ['filter' => 'auth']);
$routes->get('editLivestream/(:num)', 'Livestream::editLivestream/$1', ['filter' => 'auth']);
$routes->post('editLivestreamData', 'Livestream::editLivestreamData', ['filter' => 'auth']);
$routes->get('deleteLivestream/(:num)', 'Livestream::deleteLivestream/$1', ['filter' => 'auth']);

//livestream
$routes->get('radio', 'Radio::index', ['filter' => 'auth']);
$routes->get('newRadio', 'Radio::newRadio', ['filter' => 'auth']);
$routes->post('savenewradio', 'Radio::savenewradio', ['filter' => 'auth']);
$routes->get('editRadio/(:any)', 'Radio::editRadio/$1', ['filter' => 'auth']);
$routes->post('editRadioData', 'Radio::editRadioData', ['filter' => 'auth']);
$routes->get('deleteRadio/(:any)', 'Radio::deleteRadio/$1', ['filter' => 'auth']);

//livestream
$routes->get('photos', 'Photos::index', ['filter' => 'auth']);
$routes->get('newPhotos', 'Photos::newPhotos', ['filter' => 'auth']);
$routes->post('savenewphoto', 'Photos::savenewphoto', ['filter' => 'auth']);
$routes->get('deletePhoto/(:any)', 'Photos::deletePhoto/$1', ['filter' => 'auth']);
$routes->get('editPhoto/(:any)', 'Photos::editPhoto/$1', ['filter' => 'auth']);
$routes->post('editPhotoData', 'Photos::editPhotoData', ['filter' => 'auth']);

//admin users
$routes->get('adminListing', 'User::index', ['filter' => 'authadmin']);
$routes->get('newAdmin', 'User::newAdmin', ['filter' => 'authadmin']);
$routes->post('savenewadmin', 'User::savenewadmin', ['filter' => 'authadmin']);
$routes->get('editAdmin/(:any)', 'User::editAdmin/$1', ['filter' => 'authadmin']);
$routes->post('editadmindata', 'User::editadmindata', ['filter' => 'authadmin']);
$routes->get('deleteAdmin/(:any)', 'User::deleteAdmin/$1', ['filter' => 'authadmin']);

//settings
$routes->get('settings', 'Settings::index', ['filter' => 'auth']);
$routes->post('updatesettings', 'Settings::updatesettings', ['filter' => 'auth']);
$routes->get('profile', 'Settings::churchprofile', ['filter' => 'auth']);
$routes->post('updatechurchprofile', 'Settings::updatechurchprofile', ['filter' => 'auth']);

//admin roles and users management
$routes->group('admin', function($routes) {
	// Roles management
	$routes->get('roles', 'AdminRoles::index', ['filter' => 'auth']);
	$routes->get('roles/create', 'AdminRoles::create', ['filter' => 'auth']);
	$routes->post('roles/store', 'AdminRoles::store', ['filter' => 'auth']);
	$routes->get('roles/edit/(:num)', 'AdminRoles::edit/$1', ['filter' => 'auth']);
	$routes->post('roles/update/(:num)', 'AdminRoles::update/$1', ['filter' => 'auth']);
	$routes->get('roles/delete/(:num)', 'AdminRoles::delete/$1', ['filter' => 'auth']);
	$routes->get('roles/view/(:num)', 'AdminRoles::details/$1', ['filter' => 'auth']);

	// Users management
	$routes->get('users', 'AdminUsers::index', ['filter' => 'auth']);
	$routes->get('users/create', 'AdminUsers::create', ['filter' => 'auth']);
	$routes->post('users/store', 'AdminUsers::store', ['filter' => 'auth']);
	$routes->get('users/edit/(:num)', 'AdminUsers::edit/$1', ['filter' => 'auth']);
	$routes->post('users/update/(:num)', 'AdminUsers::update/$1', ['filter' => 'auth']);
	$routes->get('users/delete/(:num)', 'AdminUsers::delete/$1', ['filter' => 'auth']);
	$routes->post('users/assignRole/(:num)', 'AdminUsers::assignRole/$1', ['filter' => 'auth']);
	$routes->get('users/view/(:num)', 'AdminUsers::details/$1', ['filter' => 'auth']);
});

//devotionals
$routes->post('getDevotionals', 'Devotionals::getDevotionals', ['filter' => 'auth']);
$routes->get('devotionalsListing', 'Devotionals::index', ['filter' => 'auth']);
$routes->get('newDevotional', 'Devotionals::newDevotional', ['filter' => 'auth']);
$routes->post('saveNewDevotional', 'Devotionals::saveNewDevotional', ['filter' => 'auth']);
$routes->get('editDevotional/(:any)', 'Devotionals::editDevotional/$1', ['filter' => 'auth']);
$routes->post('editDevotionalData', 'Devotionals::editDevotionalData', ['filter' => 'auth']);
$routes->get('deleteDevotional/(:any)', 'Devotionals::deleteDevotional/$1', ['filter' => 'auth']);

//members
$routes->post('getMembers', 'Members::getMembers', ['filter' => 'auth']);
$routes->get('membersListing', 'Members::index', ['filter' => 'auth']);
$routes->get('newMember', 'Members::newMember', ['filter' => 'auth']);
$routes->post('saveNewMember', 'Members::saveNewMember', ['filter' => 'auth']);
$routes->get('editMember/(:any)', 'Members::editMember/$1', ['filter' => 'auth']);
$routes->get('viewMember/(:any)', 'Members::viewMember/$1', ['filter' => 'auth']);
$routes->post('editMemberData', 'Members::editMemberData', ['filter' => 'auth']);
$routes->get('deleteMember/(:any)', 'Members::deleteMember/$1', ['filter' => 'auth']);

// marketplace
$routes->get('marketplaceListing', 'Marketplace::index', ['filter' => 'auth']);
$routes->post('getMarketplaceItems', 'Marketplace::getItems', ['filter' => 'auth']);
$routes->get('newMarketplaceListing', 'Marketplace::newListing', ['filter' => 'auth']);
$routes->post('saveNewMarketplaceListing', 'Marketplace::saveNewListing', ['filter' => 'auth']);
$routes->get('editMarketplaceItem/(:num)', 'Marketplace::editListing/$1', ['filter' => 'auth']);
$routes->post('editListingData', 'Marketplace::editListingData', ['filter' => 'auth']);
$routes->get('viewMarketplaceItem/(:num)', 'Marketplace::viewItem/$1', ['filter' => 'auth']);
$routes->get('deleteMarketplaceListing/(:num)', 'Marketplace::deleteListing/$1', ['filter' => 'auth']);
$routes->get('approveMarketplaceItem/(:num)', 'Marketplace::approveItem/$1', ['filter' => 'auth']);
$routes->get('markItemSold/(:num)', 'Marketplace::markSold/$1', ['filter' => 'auth']);
$routes->post('submitMarketplaceInquiry', 'Marketplace::submitInquiry', ['filter' => 'auth']);
$routes->get('deleteMarketplaceInquiry/(:num)', 'Marketplace::deleteInquiry/$1', ['filter' => 'auth']);
$routes->get('deleteMarketplacePhoto/(:num)', 'Marketplace::deletePhoto/$1', ['filter' => 'auth']);
$routes->post('getPendingMarketplaceItems', 'Marketplace::getPendingItems', ['filter' => 'auth']);
$routes->get('rejectMarketplaceItem/(:num)', 'Marketplace::rejectItem/$1', ['filter' => 'auth']);

// marketplace mobile API (public browse: no auth; owner-scoped actions: mobiletoken)
$routes->post('fetchMarketplaceCategories', 'Api::fetchMarketplaceCategories');
$routes->post('fetchMarketplaceListings', 'Api::fetchMarketplaceListings');
$routes->post('fetchMyMarketplaceListings', 'Api::fetchMyMarketplaceListings', ['filter' => 'mobiletoken']);
$routes->post('fetchMarketplaceItem', 'Api::fetchMarketplaceItem');
$routes->post('submitMarketplaceListing', 'Api::submitMarketplaceListing', ['filter' => 'mobiletoken']);
$routes->post('uploadMarketplacePhoto', 'Api::uploadMarketplacePhoto', ['filter' => 'mobiletoken']);
$routes->post('deleteMyMarketplaceListing', 'Api::deleteMyMarketplaceListing', ['filter' => 'mobiletoken']);
$routes->post('submitMarketplaceInquiryApp', 'Api::submitMarketplaceInquiryApp');
$routes->post('updateMarketplaceListing', 'Api::updateMarketplaceListing', ['filter' => 'mobiletoken']);
$routes->get('marketplaceCategories', 'Marketplace::categories', ['filter' => 'auth']);
$routes->post('saveNewMarketplaceCategory', 'Marketplace::saveNewCategory', ['filter' => 'auth']);
$routes->get('deleteMarketplaceCategory/(:num)', 'Marketplace::deleteCategory/$1', ['filter' => 'auth']);

// partnership (admin)
$routes->get('partnership', 'Partnership::dashboard', ['filter' => 'auth']);
$routes->get('partnershipListing', 'Partnership::index', ['filter' => 'auth']);
$routes->post('getPartnershipList', 'Partnership::getList', ['filter' => 'auth']);
$routes->get('newPartnership', 'Partnership::newPartnership', ['filter' => 'auth']);
$routes->post('saveNewPartnership', 'Partnership::saveNewPartnership', ['filter' => 'auth']);
$routes->get('editPartnership/(:num)', 'Partnership::editPartnership/$1', ['filter' => 'auth']);
$routes->post('editPartnershipData', 'Partnership::editPartnershipData', ['filter' => 'auth']);
$routes->get('deletePartnership/(:num)', 'Partnership::deletePartnership/$1', ['filter' => 'auth']);
$routes->get('approvePartnership/(:num)', 'Partnership::approvePartnership/$1', ['filter' => 'auth']);
$routes->get('partnershipTiers', 'Partnership::tiers', ['filter' => 'auth']);
$routes->post('saveNewPartnershipTier', 'Partnership::saveNewTier', ['filter' => 'auth']);
$routes->post('updatePartnershipTierData', 'Partnership::updateTierData', ['filter' => 'auth']);
$routes->get('deletePartnershipTier/(:num)', 'Partnership::deleteTier/$1', ['filter' => 'auth']);
$routes->post('adminRecordPayment/(:num)', 'Partnership::adminRecordPayment/$1', ['filter' => 'auth']);
// partnership payment (public — no auth, identity by partnership ID)
$routes->get('partnerPayment/(:num)', 'Partnership::paymentPage/$1');
$routes->post('savePartnershipPayment', 'Partnership::savePartnershipPayment');
$routes->post('stripe/partnership-charge', 'Partnership::stripePartnershipCharge');

// digital counseling & case tracker (admin)
$routes->get('counseling', 'Counseling::dashboard', ['filter' => 'auth']);
$routes->post('getCounselingCaseList', 'Counseling::getCaseList', ['filter' => 'auth']);
$routes->get('newCounselingCase', 'Counseling::newCase', ['filter' => 'auth']);
$routes->post('saveNewCounselingCase', 'Counseling::saveNewCase', ['filter' => 'auth']);
$routes->get('counselingCase/(:num)', 'Counseling::viewCase/$1', ['filter' => 'auth']);
$routes->post('updateCounselingStatus/(:num)', 'Counseling::updateStatus/$1', ['filter' => 'auth']);
$routes->post('logCounselingSession', 'Counseling::logSession', ['filter' => 'auth']);
$routes->get('deleteCounselingSession/(:num)', 'Counseling::deleteSession/$1', ['filter' => 'auth']);
$routes->post('addCounselingReminder', 'Counseling::addReminder', ['filter' => 'auth']);
$routes->get('counselingReminderDone/(:num)', 'Counseling::markReminderDone/$1', ['filter' => 'auth']);
$routes->get('deleteCounselingReminder/(:num)', 'Counseling::deleteReminder/$1', ['filter' => 'auth']);
$routes->get('deleteCounselingCase/(:num)', 'Counseling::deleteCase/$1', ['filter' => 'auth']);
$routes->post('scheduleVideoSession', 'Counseling::scheduleVideoSession', ['filter' => 'auth']);
$routes->post('updateMeetingStatus/(:num)', 'Counseling::updateMeetingStatus/$1', ['filter' => 'auth']);
$routes->post('assignCounselingCase/(:num)', 'Counseling::assignCase/$1', ['filter' => 'auth']);

// partnership mobile API (fetchPartnershipTiers: public, optional personalization; rest: mobiletoken)
$routes->post('fetchPartnershipTiers', 'Api::fetchPartnershipTiers');
$routes->post('fetchMyPartnership', 'Api::fetchMyPartnership', ['filter' => 'mobiletoken']);
$routes->post('fetchPartnershipPayments', 'Api::fetchPartnershipPayments', ['filter' => 'mobiletoken']);
$routes->post('submitPartnershipPledge', 'Api::submitPartnershipPledge', ['filter' => 'mobiletoken']);
$routes->post('updatePartnershipPledge', 'Api::updatePartnershipPledge', ['filter' => 'mobiletoken']);

// counseling mobile API (mobiletoken — private case/session data)
$routes->post('submitCounselingRequest', 'Counseling::submitRequest', ['filter' => 'mobiletoken']);
$routes->post('fetchMyCounselingCases', 'Counseling::fetchMyCases', ['filter' => 'mobiletoken']);
$routes->post('fetchMyVideoSessions', 'Counseling::fetchMyVideoSessions', ['filter' => 'mobiletoken']);

// member care mobile API (mobiletoken — private wellness/pastoral-care data)
$routes->post('api/myWellnessProfile',    'MemberCareApi::myWellnessProfile', ['filter' => 'mobiletoken']);
$routes->post('api/requestPastoralCare',  'MemberCareApi::requestPastoralCare', ['filter' => 'mobiletoken']);
$routes->post('api/groupMemberBirthdays', 'MemberCareApi::groupMemberBirthdays', ['filter' => 'mobiletoken']);

// member care intelligence
$routes->get('memberCare', 'MemberCare::dashboard', ['filter' => 'auth']);
$routes->post('getMemberCareList', 'MemberCare::getCareList', ['filter' => 'auth']);
$routes->get('memberCareProfile/(:num)', 'MemberCare::profile/$1', ['filter' => 'auth']);
$routes->post('logCareEvent', 'MemberCare::logEvent', ['filter' => 'auth']);
$routes->post('addCareNote', 'MemberCare::addNote', ['filter' => 'auth']);
$routes->get('deleteCareNote/(:num)', 'MemberCare::deleteNote/$1', ['filter' => 'auth']);
$routes->get('deleteCareEvent/(:num)', 'MemberCare::deleteEvent/$1', ['filter' => 'auth']);

//articles
$routes->post('getArticles', 'Articles::getArticles', ['filter' => 'auth']);
$routes->get('articlesListing', 'Articles::index', ['filter' => 'auth']);
$routes->get('newArticle', 'Articles::newArticle', ['filter' => 'auth']);
$routes->post('saveNewArticle', 'Articles::saveNewArticle', ['filter' => 'auth']);
$routes->get('editArticle/(:any)', 'Articles::editArticle/$1', ['filter' => 'auth']);
$routes->post('editArticleData', 'Articles::editArticleData', ['filter' => 'auth']);
$routes->get('deleteArticle/(:any)', 'Articles::deleteArticle/$1', ['filter' => 'auth']);

//events
//$routes->post('getDevotionals', 'Events::getDevotionals', ['filter' => 'auth']);
$routes->get('eventsListing', 'ChurchEvents::index', ['filter' => 'auth']);
$routes->get('newEvent', 'ChurchEvents::newEvent', ['filter' => 'auth']);
$routes->post('savenewevent', 'ChurchEvents::savenewevent', ['filter' => 'auth']);
$routes->get('editEvent/(:any)', 'ChurchEvents::editEvent/$1', ['filter' => 'auth']);
$routes->post('editEventData', 'ChurchEvents::editEventData', ['filter' => 'auth']);
$routes->get('deleteEvent/(:any)', 'ChurchEvents::deleteEvent/$1', ['filter' => 'auth']);

//hymns
$routes->post('getHymns', 'Hymns::getHymns', ['filter' => 'auth']);
$routes->get('hymnsListing', 'Hymns::index', ['filter' => 'auth']);
$routes->get('newHymn', 'Hymns::newHymn', ['filter' => 'auth']);
$routes->post('saveNewHymn', 'Hymns::saveNewHymn', ['filter' => 'auth']);
$routes->get('editHymn/(:any)', 'Hymns::editHymn/$1', ['filter' => 'auth']);
$routes->post('editHymnData', 'Hymns::editHymnData', ['filter' => 'auth']);
$routes->get('deleteHymn/(:any)', 'Hymns::deleteHymn/$1', ['filter' => 'auth']);

//lists
$routes->get('lists', 'Lists::index', ['filter' => 'auth']);
$routes->get('newList', 'Lists::newList', ['filter' => 'auth']);
$routes->post('savenewlist', 'Lists::savenewlist', ['filter' => 'auth']);
$routes->get('editList/(:any)', 'Lists::editList/$1', ['filter' => 'auth']);
$routes->post('editListData', 'Lists::editListData', ['filter' => 'auth']);
$routes->get('deleteList/(:any)', 'Lists::deleteList/$1', ['filter' => 'auth']);
$routes->get('viewListMembers/(:any)', 'Lists::viewListMembers/$1', ['filter' => 'auth']);
$routes->get('addMemberstoList/(:any)', 'Lists::addMemberstoList/$1', ['filter' => 'auth']);
$routes->get('removeFromList/(:any)/(:any)', 'Lists::removeFromList/$1/$1', ['filter' => 'auth']);
$routes->post('savenewmemberslist', 'Lists::savenewmemberslist', ['filter' => 'auth']);
$routes->get('fetchlists/(:any)', 'Lists::fetchlists/$1', ['filter' => 'auth']);

//testimonies
$routes->get('testimonyListing', 'Testimony::index', ['filter' => 'auth']);
$routes->get('newTestimony', 'Testimony::newTestimony', ['filter' => 'auth']);
$routes->post('savenewtestimony', 'Testimony::savenewtestimony', ['filter' => 'auth']);
$routes->get('editTestimony/(:any)', 'Testimony::editTestimony/$1', ['filter' => 'auth']);
$routes->post('edittestimonydata', 'Testimony::edittestimonydata', ['filter' => 'auth']);
$routes->get('deleteTestimony/(:any)', 'Testimony::deleteTestimony/$1', ['filter' => 'auth']);
$routes->get('editTestimonyStatus/(:any)/(:any)', 'Testimony::editTestimonyStatus/$1/$2', ['filter' => 'auth']);

//prayers
$routes->get('prayersListing', 'Prayers::index', ['filter' => 'auth']);
$routes->get('newPrayer', 'Prayers::newPrayer', ['filter' => 'auth']);
$routes->post('savenewprayer', 'Prayers::savenewprayer', ['filter' => 'auth']);
$routes->get('viewPrayer/(:any)', 'Prayers::viewPrayer/$1', ['filter' => 'auth']);
$routes->get('editPrayer/(:any)', 'Prayers::editPrayer/$1', ['filter' => 'auth']);
$routes->post('editprayerdata', 'Prayers::editprayerdata', ['filter' => 'auth']);
$routes->get('deletePrayer/(:any)', 'Prayers::deletePrayer/$1', ['filter' => 'auth']);
$routes->get('editPrayerStatus/(:any)/(:any)', 'Prayers::editPrayerStatus/$1/$2', ['filter' => 'auth']);

//groups
$routes->get('groups', 'Groups::index', ['filter' => 'auth']);
$routes->get('newGroup', 'Groups::newGroup', ['filter' => 'auth']);
$routes->post('savenewgroup', 'Groups::savenewgroup', ['filter' => 'auth']);
$routes->get('editGroup/(:any)', 'Groups::editGroup/$1', ['filter' => 'auth']);
$routes->post('editGroupData', 'Groups::editGroupData', ['filter' => 'auth']);
$routes->get('deleteGroup/(:any)', 'Groups::deleteGroup/$1', ['filter' => 'auth']);
$routes->get('viewGroupMembers/(:any)', 'Groups::viewGroupMembers/$1', ['filter' => 'auth']);
$routes->get('addMemberstoGroup/(:any)', 'Groups::addMemberstoGroup/$1', ['filter' => 'auth']);
$routes->get('removeFromGroup/(:any)/(:any)', 'Groups::removeFromGroup/$1/$2', ['filter' => 'auth']);
$routes->post('savenewmembersgroup', 'Groups::savenewmembersgroup', ['filter' => 'auth']);
$routes->get('groupEvents/(:any)', 'Groups::groupEvents/$1', ['filter' => 'auth']);
$routes->get('editGroupMemberStatus/(:any)/(:any)', 'Groups::editGroupMemberStatus/$1/$2', ['filter' => 'auth']);


$routes->get('newGroupEvent/(:any)', 'Groups::newEvent/$1', ['filter' => 'auth']);
$routes->post('savenewgroupevent', 'Groups::savenewevent', ['filter' => 'auth']);
$routes->get('editGroupEvent/(:any)', 'Groups::editEvent/$1', ['filter' => 'auth']);
$routes->post('editGroupEventData', 'Groups::editEventData', ['filter' => 'auth']);
$routes->get('deleteGroupEvent/(:any)', 'Groups::deleteEvent/$1', ['filter' => 'auth']);

//donations
$routes->get('donations', 'Donations::index', ['filter' => 'auth']);
$routes->post('donationslisting', 'Donations::donationslisting', ['filter' => 'auth']);
$routes->get('donate', 'Donations::donate');
$routes->get('donate/(:any)', 'Donations::donate/$1');
$routes->post('savedonation', 'Donations::savedonation');
$routes->post('stripe/create-donation-charge', 'Donations::createCharge');
$routes->get('thank_you', 'Donations::thank_you');

//messaging
$routes->get('messaging', 'Messaging::index', ['filter' => 'auth']);
$routes->get('newMessage', 'Messaging::newMessage', ['filter' => 'auth']);
$routes->post('sendnewmessage', 'Messaging::sendnewmessage', ['filter' => 'auth']);
$routes->get('editMessage/(:any)', 'Messaging::editMessage/$1', ['filter' => 'auth']);
$routes->post('editMessageData', 'Messaging::editMessageData', ['filter' => 'auth']);
$routes->get('deleteMessage/(:any)', 'Messaging::deleteMessage/$1', ['filter' => 'auth']);
$routes->get('resendMessage/(:any)', 'Messaging::resendMessage/$1', ['filter' => 'auth']);

//messaging
$routes->get('inbox', 'Inbox::index', ['filter' => 'auth']);
$routes->get('newInbox', 'Inbox::newInbox', ['filter' => 'auth']);
$routes->post('sendnewinbox', 'Inbox::sendnewinbox', ['filter' => 'auth']);
$routes->get('editInbox/(:any)', 'Inbox::editInbox/$1', ['filter' => 'auth']);
$routes->post('editInboxData', 'Inbox::editInboxData', ['filter' => 'auth']);
$routes->get('deleteInbox/(:any)', 'Inbox::deleteInbox/$1', ['filter' => 'auth']);
$routes->get('resendInbox/(:any)', 'Inbox::resendInbox/$1', ['filter' => 'auth']);

//books
$routes->get('books', 'Books::index', ['filter' => 'auth']);
$routes->get('newBook', 'Books::newBook', ['filter' => 'auth']);
$routes->post('saveNewBook', 'Books::saveNewBook', ['filter' => 'auth']);
$routes->get('editBook/(:any)', 'Books::editBook/$1', ['filter' => 'auth']);
$routes->post('editBookData', 'Books::editBookData', ['filter' => 'auth']);
$routes->get('deleteBook/(:any)', 'Books::deleteBook/$1', ['filter' => 'auth']);

//api routes
$routes->post('storefcmtoken', 'Api::storeFcmToken');
$routes->post('initapp', 'Api::initapp');
$routes->post('getunseenmessages', 'Api::getunseenmessages');
//acount
$routes->post('loginapp', 'Api::loginapp');
$routes->post('createaccount', 'Api::createaccount');
$routes->post('resendVerificationMail', 'Api::resendVerificationMail');
$routes->post('resetpassword', 'Api::resetpassword');
$routes->get('resetLink/(:any)', 'Account::resetLink/$1');
$routes->get('verifyEmailLink/(:any)', 'Account::verifyEmailLink/$1');
$routes->post('changeUserPassword', 'Account::changeUserPassword');
$routes->post('updateUserProfile', 'Api::updateUserProfile');
$routes->post('deletemyaccount', 'Api::deletemyaccount');
//others
$routes->post('getitemdata', 'Api::getitemdata');
$routes->post('fetchmedia', 'Api::fetchmedia');
$routes->post('fetchphotos', 'Api::fetchphotos');
$routes->post('fetchradios', 'Api::fetchradios');
$routes->post('fetchlivestreams', 'Api::fetchlivestreams');
$routes->post('fetchbooks', 'Api::fetchbooks');
$routes->post('fetcharticles', 'Api::fetcharticles');
$routes->post('fetchbranches', 'Api::fetchbranches');
$routes->post('fetchgroups', 'Api::fetchgroups');
$routes->post('fetchgroupevents', 'Api::fetchgroupevents');
$routes->post('fetchmygroups', 'Api::fetchmygroups');
$routes->post('joingroup', 'Api::joingroup');
$routes->post('fetchprayers', 'Api::fetchprayers');
$routes->post('fetchtestimonies', 'Api::fetchtestimonies');
$routes->post('submittestimony', 'Api::submittestimony');
$routes->post('submitprayer', 'Api::submitprayer');
$routes->post('update_media_total_views', 'Api::update_media_total_views');
$routes->post('getBibleVersions', 'Api::getBibleVersions');
$routes->post('fetch_events', 'Api::fetch_events');
$routes->post('fetch_devotionals', 'Api::fetch_devotionals');
$routes->post('fetch_hymns', 'Api::fetch_hymns');
$routes->post('fetch_inbox', 'Api::fetch_inbox');
$routes->post('search', 'Api::search');
$routes->get('testemail', 'Api::testemail');

//socials and chats
$routes->post('updateUserSocialFcmToken', "Socials::updateUserSocialFcmToken");
$routes->post('make_post', 'Socials::make_post');
$routes->post('fetch_posts', 'Socials::fetch_posts');
$routes->post('likeunlikepost', "Socials::likeunlikepost");
$routes->post('pinunpinpost', "Socials::pinunpinpost");
$routes->post('editpost', "Socials::editpost");
$routes->post('deletepost', "Socials::deletepost");
$routes->post('post_likes_people', "Socials::post_likes_people");

$routes->post('makepostcomment', "Socials::makecomment");
$routes->post('editpostcomment', "Socials::editcomment");
$routes->post('deletepostcomment', "Socials::deletecomment");
$routes->post('loadpostcomments', "Socials::loadcomments");
$routes->post('reportpostcomment', "Socials::reportcomment");
$routes->post('replypostcomment', "Socials::replycomment");
$routes->post('editpostreply', "Socials::editreply");
$routes->post('deletepostreply', "Socials::deletereply");
$routes->post('loadpostreplies', "Socials::loadreplies");

$routes->post('get_users_to_follow', "Socials::get_users_to_follow");
$routes->post('userNotifications', "Socials::userNotifications");
$routes->post('deleteNotification', "Socials::deleteNotification");
$routes->post('setSeenNotifications', "Socials::setSeenNotifications");
$routes->post('getUnSeenNotifications', "Socials::getUnSeenNotifications");
$routes->post('userBioInfo', "Socials::userBioInfo");
$routes->post('fetch_user_settings', "Socials::fetch_user_settings");
$routes->post('update_user_settings', "Socials::update_user_settings");
$routes->post('fetchUserPins', "Socials::fetchUserPins");

//chat
$routes->post('fetch_user_chats', 'Chat::fetch_user_chats');
$routes->post('load_more_chats', 'Chat::load_more_chats');
$routes->post('fetch_user_partner_chat', 'Chat::fetch_user_partner_chat');
$routes->post('save_user_conversation', 'Chat::save_user_conversation');
$routes->post('on_seen_conversation', 'Chat::on_seen_conversation');
$routes->post('on_user_typing', 'Chat::on_user_typing');
$routes->post('update_user_online_status', 'Chat::update_user_online_status');
$routes->post('delete_selected_chat_messages', 'Chat::delete_selected_chat_messages');
$routes->post('clear_user_conversation', 'Chat::clear_user_conversation');
$routes->post('blockUnblockUser', 'Chat::blockUnblockUser');
$routes->post('checkfornewmessages', 'Chat::checkfornewmessages');



//admin roles and users management
$routes->get('admin/roles', 'AdminRoles::index', ['filter' => 'auth']);
$routes->get('admin/roles/create', 'AdminRoles::create', ['filter' => 'auth']);
$routes->post('admin/roles/store', 'AdminRoles::store', ['filter' => 'auth']);
$routes->get('admin/roles/edit/(:num)', 'AdminRoles::edit/$1', ['filter' => 'auth']);
$routes->post('admin/roles/update/(:num)', 'AdminRoles::update/$1', ['filter' => 'auth']);
$routes->get('admin/roles/delete/(:num)', 'AdminRoles::delete/$1', ['filter' => 'auth']);
// Note: admin/roles/view route is defined in the grouped routes section above

// Setup routes - no auth filter for initial setup
$routes->get('setup/permissions', 'SetupController::setupPermissions');
$routes->get('setup/check-permissions', 'SetupController::checkPermissions');

$routes->get('admin/users', 'AdminUsers::index', ['filter' => 'auth']);
$routes->get('admin/users/create', 'AdminUsers::create', ['filter' => 'auth']);
$routes->post('admin/users/store', 'AdminUsers::store', ['filter' => 'auth']);
$routes->get('admin/users/edit/(:num)', 'AdminUsers::edit/$1', ['filter' => 'auth']);
$routes->post('admin/users/update/(:num)', 'AdminUsers::update/$1', ['filter' => 'auth']);
$routes->get('admin/users/delete/(:num)', 'AdminUsers::delete/$1', ['filter' => 'auth']);
$routes->post('admin/users/assignRole/(:num)', 'AdminUsers::assignRole/$1', ['filter' => 'auth']);
// Note: admin/users/view route is defined in the grouped routes section above

//other pages
$routes->get('mobileAdverts', 'MobileAdverts::index', ['filter' => 'auth']);
$routes->post('mobileAdverts/store', 'MobileAdverts::store', ['filter' => 'auth']);
$routes->get('mobileAdverts/toggle/(:num)', 'MobileAdverts::toggle/$1', ['filter' => 'auth']);
$routes->get('mobileAdverts/delete/(:num)', 'MobileAdverts::delete/$1', ['filter' => 'auth']);
$routes->get('mobile-adverts/feed', 'MobileAdverts::feed');
$routes->get('terms', 'Settings::terms');
$routes->get('privacy', 'Settings::privacy');
$routes->get('aboutus', 'Settings::aboutus');

//payments
$routes->get('makepayment', 'Payments::makepayment', ['filter' => 'auth']);
$routes->post('stripe/create-charge', 'Payments::createCharge', ['filter' => 'auth']);
$routes->post('savesubscription', 'Payments::savesubscription', ['filter' => 'auth']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
