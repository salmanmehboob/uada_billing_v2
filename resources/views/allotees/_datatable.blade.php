<script>
    const dt_responsive_table = document.querySelector('.datatable');
    if (dt_responsive_table) {
        let dt_responsive = new DataTable(dt_responsive_table, {
            destroy: true,
            processing: true,
            serverSide: true,
            deferRender: true,
            ajax: {
                url: "{{ route('admin.allotees.data') }}",
                type: 'GET'
            },
            order: [[0, 'desc']],
            pageLength: 25,

            columns: [
                { data: 'id', name: 'id' },

                 { data: 'name', name: 'name', className: 'text-wrap' },

                // If alignment is not okay for you, you can show these separate columns instead
                // and optionally hide the combined one by toggling column visibility.
                { data: 'plot_no', name: 'plot_no'    },
                { data: 'size_name', name: 'size_name',   },
                { data: 'sector_name', name: 'sector_name'  },
                { data: 'type_name', name: 'type_name'  },

                // Keep other columns as needed
                 { data: 'phone_no', name: 'phone_no' },
                { data: 'arrears', name: 'arrears' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],


            layout: {
                topStart: {
                    rowClass: 'row m-3 my-0 justify-content-between',
                    features: [
                        {
                            pageLength: {
                                menu: [10, 25, 50, 100],
                                text: '_MENU_'
                            }
                        }
                    ]
                },
                topEnd: {
                    features: [
                        {
                            search: {
                                placeholder: 'Search',
                                text: '_INPUT_'
                            }
                        },
                        {
                            buttons: [
                                {
                                    extend: 'collection',
                                    className: 'btn btn-label-secondary dropdown-toggle m-2',
                                    text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-upload icon-xs"></i> <span class="d-none d-sm-inline-block">Export</span></span>',
                                    buttons: [
                                        {
                                            extend: 'print',
                                            text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-printer me-1"></i>Print</span>`,
                                            className: 'dropdown-item',
                                            exportOptions: {columns: ':not(:last-child)'},
                                            orientation: dt_responsive_table.querySelectorAll('th').length > 5 ? 'landscape' : 'portrait',
                                            pageSize: 'A4'

                                        },
                                        {
                                            extend: 'csv',
                                            text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-text me-1"></i>Csv</span>`,
                                            className: 'dropdown-item',
                                            exportOptions: {columns: ':not(:last-child)'},

                                        },
                                        {
                                            extend: 'excel',
                                            text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-spreadsheet me-1"></i>Excel</span>`,
                                            className: 'dropdown-item',
                                            exportOptions: {columns: ':not(:last-child)'},
                                        },
                                        {
                                            extend: 'pdf',
                                            text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-description me-1"></i>Pdf</span>`,
                                            className: 'dropdown-item',
                                            exportOptions: {columns: ':not(:last-child)'},
                                            orientation: dt_responsive_table.querySelectorAll('th').length > 5 ? 'landscape' : 'portrait',
                                            pageSize: 'A4'
                                        },
                                        {
                                            extend: 'copy',
                                            text: `<i class="icon-base ti tabler-copy me-1"></i>Copy`,
                                            className: 'dropdown-item',
                                            exportOptions: {columns: ':not(:last-child)'},
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                bottomStart: {
                    rowClass: 'row mx-3 justify-content-between',
                    features: ['info']
                },
                bottomEnd: 'paging'
            },
            language: {
                sLengthMenu: '_MENU_',
                search: '',
                searchPlaceholder: 'Search User',
                paginate: {
                    next: '<i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>',
                    previous: '<i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>',
                    first: '<i class="icon-base ti tabler-chevrons-left scaleX-n1-rtl icon-18px"></i>',
                    last: '<i class="icon-base ti tabler-chevrons-right scaleX-n1-rtl icon-18px"></i>'
                }
            },
            responsive: {
                details: {
                    display: DataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['name'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        const data = columns
                            .map(function (col) {
                                return col.title !== ''
                                    ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                                        <td>${col.title}:</td>
                                        <td>${col.data}</td>
                                    </tr>`
                                    : '';
                            })
                            .join('');

                        if (data) {
                            const div = document.createElement('div');
                            div.classList.add('table-responsive');
                            const table = document.createElement('table');
                            div.appendChild(table);
                            table.classList.add('table');
                            const tbody = document.createElement('tbody');
                            tbody.innerHTML = data;
                            table.appendChild(tbody);
                            return div;
                        }
                        return false;
                    }
                }
            }
        });
    }
</script>
