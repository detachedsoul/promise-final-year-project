<?php require_once("includes/Header.php") ?>

<div class="container-lg">
    <div class="row">
        <div class="col-sm-6 col-lg-4">
            <div class="card mb-4 text-white bg-primary">
                <div class="card-body pb-0 d-grid g-4">
                    <h2 class="h4">
                        Total received complains
                    </h2>

                    <p class="h3">
                        <?= countTotalComplains() ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card mb-4 text-white bg-warning">
                <div class="card-body pb-0 d-grid g-4">
                    <h2 class="h4">
                        Total pending complains
                    </h2>

                    <p class="h3">
                        <?= countPendingComplains() ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card mb-4 text-white bg-success">
                <div class="card-body pb-0 d-grid g-4">
                    <h2 class="h4">
                        Total resolved complains
                    </h2>

                    <p class="h3">
                        <?= countResolvedComplains() ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 bg-white p-4">
            <h2 class="mb-4">
                Complains
            </h2>

            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Filled By</th>
                        <th scope="col">Email</th>
                        <th scope="col">Matric No</th>
                        <th scope="col">Complain Subject</th>
                        <th scope="col">Complain</th>
                        <th scope="col">Dated</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td class="text-danger">Pending</td>
                        <td>
                            <a class="btn btn-primary" href="">
                                View Complain
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once("includes/Footer.php"); ?>