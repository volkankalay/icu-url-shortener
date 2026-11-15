document.querySelectorAll('[data-admin-table]').forEach((element) => {
    const panel = element.closest('.admin-table-panel');
    const sort = panel.querySelector('[data-admin-sort]');
    const order = () => {
        const [column, direction] = sort.value.split(':');
        return [[Number(column), direction]];
    };
    const table = $(element).DataTable({
        dom: 't<"links-table-footer"ip>',
        autoWidth: false,
        orderMulti: false,
        order: order(),
        columnDefs: [{ targets: -1, orderable: false, searchable: false }],
        language: {
            info: '_TOTAL_ kayıttan _START_–_END_',
            infoEmpty: 'Kayıt yok',
            infoFiltered: '(toplam _MAX_)',
            zeroRecords: 'Eşleşen kayıt yok',
            paginate: { previous: 'Önceki', next: 'Sonraki' }
        }
    });

    panel.querySelector('[data-admin-toolbar]').hidden = false;
    panel.querySelector('[data-admin-search]').addEventListener('input', (event) => table.search(event.target.value).draw());
    panel.querySelector('[data-admin-page-size]').addEventListener('change', (event) => table.page.len(Number(event.target.value)).draw());
    sort.addEventListener('change', () => table.order(order()).draw());
    table.on('order.dt', () => {
        sort.value = table.order()[0].join(':');
    });
});
