<?php

return [
    'title' => 'All Votes',
    'subtitle' => 'Explore recent votes or search our database by subject.',

    'share-picture' => [
        'footer' => 'Find out how individual MEPs, political groups, and countries voted:',
        'subtitle' => 'Result of the vote in the European Parliament',
        'summary' => 'In total, <strong>:voted MEPs</strong> voted. <strong>:did-not-vote MEPs</strong> didn’t vote.',
        'alt-text' => 'A barchart visualizing the result of the European Parliaments vote on ":title". The vote was held on :date. The barchart has three bars, representing the :for MEPs who voted in favor (:forpercent%), the :against MEPs who votes against (:againstpercent%), and the :abstention MEPs who did abstain (:abstentionpercent%). In total, :voted MEPs participated in the vote and :novote MEPs did not vote.',
    ],

    'for' => 'For',
    'against' => 'Against',
    'abstentions' => 'Abstentions',

    'members' => [
        'title' => 'MEPs',
        'search-placeholder' => 'Search by name, country, or group',
        'show-more' => 'Show all MEPs',
    ],

    'groups' => [
        'title' => 'Political Groups',
        'count' => ':voted of :total MEPs voted',
    ],

    'countries' => [
        'title' => 'Countries',
        'search-placeholder' => 'Search by name',
        'show-more' => 'Show all countries',
    ],

    'download' => [
        'heading' => 'Open Data',
        'text' => 'We provide raw voting data for this vote, ready to use in your own analyses, visualizations or applications. Data is provided in CSV or JSON format and licensed under Creative Commons Attribution License 4.0.',
        'button-label-csv' => 'CSV',
        'button-label-json' => 'JSON',
    ],

    'related-votes-list' => [
        'heading' => 'Related Votes',
        'text' => 'There were previous votes on this subject, like amendments or split-votes.',
        'button-label' => 'View votes',
    ],

    'non-final-callout' => [
        'heading' => 'This is not the final vote!',
        'amendment' => 'This is a vote on :amendment.',
        'separate' => 'This is a :separate.',
        'text' => 'View the <a href=":url">result of the final vote</a> on this subject.',
    ],

    'summary' => [
        'read-more' => 'Read more',
    ],
];
