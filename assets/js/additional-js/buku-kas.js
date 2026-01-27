var tsupp;
var monthNames = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];
var formatcur = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
});
$(document).ready(function () {
    reload();
    getid();
});

function reload() {
    if ($.fn.DataTable.isDataTable('#table-buku-kas')) {
      tsupp.destroy();
    }
    tsupp = $("#table-buku-kas").DataTable({
        "processing": true,
        'iDisplayLength': 15,
        "language": { 
            "processing": '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
            // "url": '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',            
        },
        "serverSide": true,
        "order": [
          [0, 'asc']
        ],
        "ajax": {
            "url": base_url+'buku-kas/jsonkas?tahun='+$('#tahun').val(),
            "type": "POST"
        },
        "columns": [
            { "data": "id_bulan"},
            { "data": "nama_bulan" },
            { "data": "tahun"},
            { "data": "saldo_awal", searchable: false, render: function(data, type, row) {
                return formatcur.format(data);
              }},
            { "data": "saldo_akhir", searchable: false, render: function(data, type, row) {
                return formatcur.format(data);
              }},
            { 
              "data": "nama_bulan",
              "orderable": false, // Disable sorting for this column
              "render": function(data, type, full, meta) {
                    return `
                      <ul class="action">
                        <li class="edit">
                          <button class="btn" id="edit-btn" type="button" data-bulan="${full.id_bulan}" data-tahun="${full.tahun}" data-bs-toggle="modal" data-bs-target="#EditBukuKasModal"><i class="icon-pencil"></i></button>
                        </li>
                    `;
              }
            }
          ],    
          "order": [[0, 'asc']],
    });

    tsupp.on('draw', function() {
        tsupp.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
            tsupp.cell(cell).invalidate('dom');
        });
    }).draw();
}

function getid(){
  $('#EditBukuKasModal').on('show.bs.modal', function (e) {
    var button = $(e.relatedTarget);
    var id_bulan = button.data('bulan');
    var id_tahun = button.data('tahun');
      
      $.ajax({
          url: base_url + "buku-kas/edit/"+id_bulan+"/"+id_tahun,
          dataType: "json",
          success: function(data) {
            $("#sbulan").val(id_bulan);
            $("#ebulan").val(id_bulan);
            $("#stahun").val(id_tahun);
            $("#etahun").val(id_tahun);
              $.each(data.get_id, function(index, item) {
                  $("#esaldo_awal").val(item.saldo_awal);
                  $("#esaldo_akhir").val(item.saldo_akhir);
              });
          }
      });
      updatedata();
  });
}
function updatedata(){
  $("#update").on("click", function (){
      $.ajax({
          type: "POST",
          url: base_url + "buku-kas/update-data",
          data: {
              ebulan: $("#ebulan").val(),
              etahun: $("#etahun").val(),
              esaldo_awal: $("#esaldo_awal").val(),
              esaldo_akhir: $("#esaldo_akhir").val(),
          },
          dataType: "json", 
          success: function (response) {
              if (response.status === 'success') {
                  swal("Data berhasil diupdate", {
                      icon: "success",
                  }).then((value) => {
                      reload();
                  });
              } else {
                  swal("Gagal update data", {
                      icon: "error",
                  });
              }
          },
          error: function (error) {
              swal("Gagal", {
                  icon: "error",
              });
          }
      });
  });
}