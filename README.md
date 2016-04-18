# See Who's Online With This Laravel Package

With this package, you can easily see who's online and how many guests are viewing your site.

## Installation

To install this package, just follow these quick few steps.

### Composer

As always, pull this package through composer by opening `composer.json` file and adding this within `require`:

```
"kim/activity": "^1.1"
```

Note: If you are running Laravel 5.0 or 5.1, please require version "^1.0".

Afterward, run either `composer update`.

### Providers and Aliases

Next, open `config/app.php` and add this to your providers array:

```
Kim\Activity\ActivityServiceProvider::class
```

and this to your aliases array:

```
'Activity' => Kim\Activity\ActivityFacade::class
```

### Session and Database Setup

Finally, you need to change your session configuration to use the database. Open the `.env` file, which should be at the root directory of your Laravel project. Then, change your session driver to database.

```
SESSION_DRIVER=database
```

If you are running L5.2, publish the default session migrations file and then migrate it by running these commands:

```
php artisan vendor:publish

php artisan migrate
```

If you are running L5.0 or L5.1, run these commands:

```
php artisan vendor:publish --provider="Kim\Activity\ActivityServiceProvider" --tag="migrations"

php artisan migrate
```

## Usage

This package will automatically update a user's or guest's most recent activity. To grab the most recent users and guests though, you can use the easy-to-use built-in methods.

### Grabbing Most Recent Activities

Import the Activity facade at the top and then do a simple query.

```
// Import at the top
use Activity;


// Find latest users
$activities = Activity::users()->get();

// Loop through and echo user's name
foreach ($activities as $activity) {
    echo $activity->user->name . '<br>';
}
```

The `users` method will grab the most recent activities within the past 5 minutes. You can change the default timespan by specifying the minutes.

```
$activities = Activity::users(1)->get();   // Last 1 minute
$activities = Activity::users(10)->get();  // Last 10 minutes
$activities = Activity::users(60)->get();  // Last 60 minutes
```

You have other methods for your convenience to grab the latest activities by seconds or even hours.

```
$activities = Activity::usersBySeconds(30)->get();  // Get active users within the last 30 seconds
$activities = Activity::usersByMinutes(10)->get();  // Get active users within the last 10 minutes
$activities = Activity::usersByHours(1)->get();     // Get active users within the last 1 hour

$numberOfUsers = Activity::users()->count();        // Count the number of active users
```

### Sorting Methods

In order to sort the activities by most and least recent, just use the `mostRecent` and `leastRecent` methods.

```
$activities = Activity::users()->mostRecent()->get();   // Get active users and sort them by most recent
$activities = Activity::users()->leastRecent()->get();  // Get active users and sort them by least recent
```

In addition to this, you can sort the user's attributes by using the `orderByUsers` method. For example, rather than ordering by the most recent activity, lets say you want to order by the users' name alphabetically. You can do this.

```
$activities = Activity::users()->orderByUsers('email')->get();
```

### Grabbing the Guests

In order to grab the number of guests that are online, it's just as intuitive as grabbing the users. For example:

```
$numberOfGuests = Activity::guests()->count();      // Count the number of active guests
```

## License

This package is free software distributed under the terms of the MIT license.