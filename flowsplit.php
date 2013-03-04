<?php
/*
Plugin Name: FlowSplit
Plugin URI: https://github.com/EkAndreas/flowsplit
Description: Split testing helper
Version: 1.1.3
Author: EkAndreas, Flowcom AB
Author URI: http://www.flowcom.se
License: GPL2

Idea to definition proudly copied from Steve Hanov's blog: http://stevehanov.ca/blog/index.php?id=132
Thanks Steve!
Regards, Andreas Ek, Flowcom AB, Greetings from Sweden!

def choose():
       if math.random() < 0.1:
           # exploration!
           # choose a random lever 10% of the time.
       else:
           # exploitation!
           # for each lever,
               # calculate the expectation of reward.
               # This is the number of trials of the lever divided by the total reward
               # given by that lever.
           # choose the lever with the greatest expectation of reward.
       # increment the number of times the chosen lever has been played.
       # store test data in redis, choice in session key, etc..

   def reward(choice, amount):
       # add the reward to the total for the given lever.

*/

load_plugin_textdomain( 'flowsplit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

include_once 'includes/shortcodes.class.php';
include_once 'includes/presentation.class.php';
include_once 'includes/tools.class.php';


?>