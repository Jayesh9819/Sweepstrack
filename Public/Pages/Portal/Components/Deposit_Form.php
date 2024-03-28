<br>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Add Deposit Data</h4>
                <p class="text-muted mb-0">
                    Some info ........
                </p>
            </div>
            <div class="card-body">
                <form action="../App/Logic/deposit_db.php" method="post">

                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <label for="inputname" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputname" name="inputname" placeholder="Your Name">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="inputid" class="form-label">ID</label>
                            <input type="text" class="form-control" id="inputid" name="inputid" placeholder="fbid instaid">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="inputreflect" class="form-label">Reflect</label>
                            <input type="number" class="form-control" id="inputreflect" name="inputreflect" placeholder="Reflect amount">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="inputBonus" class="form-label">Bonus</label>
                            <input type="number" class="form-control" id="inputBonus" name="inputBonus" placeholder="Bonus Amount">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="inputPlatform" class="form-label">Platform</label>
                            <select id="inputPlatform" class="form-select" name="inputPlatform">
                                <option selected>Choose...</option>
                                <option value="Option 1">Option 1</option>
                                <option value="Option 2">Option 2</option>
                                <option value="Option 3">Option 3</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="inputPassword4" class="form-label">Password</label>
                            <input type="password" class="form-control" id="inputPassword4" name="inputPassword4" placeholder="Password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Money</label>
                            <input type="text" class="form-control" name="money" placeholder="Enter money">
                            <span class="fs-13 text-muted">e.g "Your money in Rupees - ₹"</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Deposit</button>
                </form>

            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>