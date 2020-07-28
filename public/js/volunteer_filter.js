$(document).ready(function () {
    const statusContainer = $("#volunteer-filter-status");
    const filterListContainer = $("#volunteer-filter-list-container");
    const textInput = $("#volunteer-filter-list-input");
    const volunteerList = $("#volunteer-list-body");

    /**
     * Contain a list of Filter
     *
     * A `Filter` is an Object of this shape:
     * * Filter.id : Integer id of the filter
     * * Filter.name : Pretty name of the filter
     * * Filter.type : Type of the filter, either `blacklist` or `whitelist`
     */
    const filters = [];

    /** contain the first filter id of the dropdown list */
    let firstFilter = null;

    // Hide and show filter-list dropdown
    textInput.on("focus", updateFilterDropdown);
    textInput.on("click", (event) => {
        event.stopPropagation();
    });
    filterListContainer.on("click", (event) => {
        event.stopPropagation();
    });
    $(document).on("click", (event) => {
        filterListContainer.hide();
    });

    // Update list dropdown on text input change
    textInput.on("input", updateFilterDropdown);

    // Add filter button
    $(".volunteer-filter-add").on("click", function (event) {
        const id = $(this).parent().data("id");
        const name = $(this).parent().data("name");
        const type = $(this).data("type");
        addToList(id, name, type);
        executeFilter();
    });

    // On press enter add the first filter of the list as whitelist
    textInput.on("keyup", function(e) {
        if (e.keyCode == 13 && firstFilter !== null) {
            addToList(firstFilter.id, firstFilter.name, 'whitelist');
            executeFilter();
        }
    });

    /**
     * Remove a filter from all the lists
     * @param {any} id ID of the filter to remove from the list
     */
    function removeFromList(id) {
        const index = filters.findIndex((v) => v.id === id);
        if (index !== -1) {
            filters.splice(index, 1);
        }
    }

    /**
     * Add a filter to the list and remove it from the other list
     * @param {any} id ID of the filter to add to the list
     * @param {string} type `whitelist` or `blacklist`
     */
    function addToList(id, name, type) {
        removeFromList(id);
        filters.push({ id, name, type });
    }

    /**
     * Show and pdate filter dropdown list
     */
    function updateFilterDropdown() {
        filterListContainer.show();

        // Filter the "filter list"
        firstFilter = null;
        let value = textInput.val().toLowerCase();
        let items = filterListContainer.children();
        items.each((key, child) => {
            child = $(child);
            let name = child.data("name");
            if (name.toLowerCase().indexOf(value) !== -1) {
                child.show();
                if (firstFilter === null) {
                    firstFilter = {
                        id: child.data('id'),
                        name,
                    };
                }
            } else {
                child.hide();
            }
        });
    }

    /**
     * Execute the main volunteer filter based on `whitelist` and `blacklisŧ`
     */
    function executeFilter() {
        // Update the top bar filter list
        let html = "";
        for (filter of filters) {
            const color = filter.type == "whitelist" ? "success" : "danger";
            html +=  (
                `<div class="label label-${color}">${filter.name}` +
                `<button type="button" class="close cancel-filter" data-id="${filter.id}">` +
                '<span aria-hidden="true">&times;</span>' +
                "</button></div>&nbsp;"
            );
        }
        statusContainer.html(html);

        // Update cancel filter events
        statusContainer.find(".cancel-filter").on("click", function (event) {
            const id = $(this).data("id");
            removeFromList(id);
            executeFilter();
            event.stopPropagation();
        });

        // Filter the volunteer list
        let items = volunteerList.children();
        items.each((key, child) => {
            // Computer filter result on this item
            IDs = String($(child).data('filtering-ids')).split('|');
            let hide = false;
            for (const filter of filters) {
                if (filter.type == 'whitelist' && !IDs.includes(String(filter.id))) {
                    hide = true;
                }
                else if (filter.type == 'blacklist' && IDs.includes(String(filter.id))) {
                    hide = true;
                }
            }

            // Hide or show based on filter result
            if (hide) {
                $(child).hide();
            }
            else {
                $(child).show();
            }
        })
    }
});
