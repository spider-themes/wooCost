jQuery(document).ready(function ($) {

    // Access the localized data
    console.log(costOperationData.data);

// Example: Loop through and display in the console
    costOperationData.data.forEach((item) => {
        console.log(`ID: ${item.id}`);
        console.log(`Title: ${item.title}`);
        console.log(`Cost: ${item.cost}`);
        console.log(`Account: ${item.account}`);
        console.log(`Notes: ${item.notes}`);
        console.log(`Date: ${item.date}`);
        console.log(`File: ${item.file}`);
        console.log('--------------------');
    });

})