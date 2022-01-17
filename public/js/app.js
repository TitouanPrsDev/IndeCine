(function () {
    let firstTab = document.querySelector('.dates .date:first-child');
    firstTab.classList.add('active');

    let screeningsList = document.querySelectorAll('.screenings .date');

    screeningsList.forEach(screening => {
        if (screening.classList.contains('date-1')) return;

        screening.classList.add('hidden');
    });
})();

function onSelectSubmit() {
    document.querySelector('.show').submit();
}

function switchTab(e) {
    let datesList = document.querySelectorAll('.dates .date');
    let screeningsList = document.querySelectorAll('.screenings .date');

    if (!e.classList.contains('active')) {
        let tabNumber = e.classList[1].substr(-1, 1);

        screeningsList.forEach(screening => {
            if (!screening.classList.contains('hidden')) screening.classList.add('hidden');
            if (screening.classList[1].substr(-1, 1) === tabNumber) screening.classList.remove('hidden');
        });
    }

    datesList.forEach(date => {
        if (date.classList.contains('active')) date.classList.remove('active');
    });

    e.classList.add('active');
}

function checkAll(e) {
    let checkboxes = document.querySelectorAll("tbody input[type='checkbox']");

    checkboxes.forEach(checkbox => {
        checkbox.checked = !!e.checked;
    });
}