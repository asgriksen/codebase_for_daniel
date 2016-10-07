# Endpoints

####Production API URL
https://ads.viralstyle.com

##POST api/users/auth

Logs the user into their account.

####Parameters

######email: string (required)
The users email address.

######password: string (required)
The users password.

####Events

######auth.login
This event is fired by default via Laravel when the user successfully logs in using `Auth::attempt()`. There is a subscribed event listener (`App\Listeners\UserEventListener`) that will listen for this event. When the event is encountered it will call the `App\Listeners\UserEventListener::onLogin()` method which updates the `users` table `online_check_at` field via the user repository.

##GET api/users/auth/logout

Logs the user out of their account

##POST api/users/online

Updates the users `online_check_at` timestamp field in the `users` table

##GET api/users/info

Returns the user record of the logged in user.

##GET api/accounts

Return a list of all of the user's ad accounts.

##GET api/accounts/{id}

Returns an individual ad account record.

##POST api/accounts

Creates a new account record.

####Parameters

######fb\_account_id: integer (required)

The Facebook account id.

######fb_token: string (required)

The Facebook account access token.

####Observers

######App\Observers\AccountObserver::created()

This registered observer observes when a new account record has been created. Upon detection it will fire the `App\Events\AccountWasCreated` event.

####Events

######App\Events\AccountWasCreated

This event has an associated listenter (`App\Listeners\ImportAccountFacebookData`).

####Listeners

######App\Listeners\ImportAccountFacebookData

This listener is programmed to interact with the queue. When it is processed it will import the ad account details along with its ad campaigns, ad sets, and ads from the Facebook Ads API.

##PUT api/accounts/{id}
Update and individual account record with new data.

###Parameters

######is_selected: boolean (required)

Update is the users want to use this account.

##DELETE api/accounts/{id}

Soft-deletes an individual account record.

##GET api/ad-sets

Returns a listing of ad sets that belong to campaigns related to the ad account. Facebook metrics will be included in the response data.

**NOTE:** If no `start_date` or `end_date` is specified it will return metrics for the past 7 days.

###Parameters

######account_id: integer (required)

The id of the ad account we want data from.

######start_date: string (required)

Format: yyyy-mm-dd

Only returns metric data after this date.

######end_date: string (required)

Format: yyyy-mm-dd

Only returns metric data before this date.

##GET api/ad-sets/{id}

Returns a particular ad set record along with its Facebook metric data.

**NOTE:** If no `start_date` or `end_date` is specified it will return metrics for the past 7 days.

###Parameters

######account_id: integer (required)

The id of the ad account we want data from.

######start_date: string (required)

Format: yyyy-mm-dd

Only returns metric data after this date.

######end_date: string (required)

Format: yyyy-mm-dd

Only returns metric data before this date.

##GET api/cart-categories?cart_id={id}

Returns a tree structure of cart categories for the specified cart_id

**NOTE:** If no cart_id is specified, it will return error.

###Parameters

######cart_id: integer (required)

##GET api/cart_categories/{id}

Returns a tree structure of cart categories for the specified category_id

###Parameters

######id: integer (required)

category_id