const tabs = document.querySelectorAll('.tab');
const tabContents = document.querySelectorAll('.tab-content');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        // Remove active class from all tabs and tab contents
        tabs.forEach(t => t.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        // Add active class to clicked tab and corresponding content
        tab.classList.add('active');
        document.getElementById(tab.getAttribute('data-tab')).classList.add('active');
    });
});

const addRuleBtn = document.getElementById('addRuleBtn');
const ruleInput = document.getElementById('ruleInput');

addRuleBtn.addEventListener('click', function () {
    ruleInput.style.display = 'block'; // Show the input field
});