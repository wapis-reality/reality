<?php
interface DataSourceLayerInterface
{
    public function connect();

    public function find();

    public function read($fields = null, $id);

    public function query();

    public function delete();

    public function _insert();

    public function _update();
}