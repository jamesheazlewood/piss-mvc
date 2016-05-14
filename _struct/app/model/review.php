<?php
// review model class example
class Review extends Model
{
	// constructor
	function __construct($db) {
		// call parent
		parent::__construct($db);
	}

	//
	function findAll() {
		//
		$sql = "SELECT * FROM reviews ORDER BY title ASC";
		$statement = $this->db->prepare($sql);
		$statement->execute();

		//
		return $statement->fetchAll();
	}

	//
	function findLatest()	{
		//
		$sql = "SELECT * FROM reviews ORDER BY date DESC LIMIT 10";
		$statement = $this->db->prepare($sql);
		$statement->execute();

		//
		return $statement->fetchAll();
	}

	//
	function findBySlug($slug)	{
		//
		$sql = "SELECT * FROM reviews WHERE slug = :slug";
		$statement = $this->db->prepare($sql);
		$statement->execute(array(':slug' => $slug));

		//
		return $statement->fetch();
	}

	//
	function findById($id)	{
		//
		$sql = "SELECT * FROM reviews WHERE id = :id";
		$statement = $this->db->prepare($sql);
		$statement->execute(array(':id' => $id));

		//
		return $statement->fetch();
	}

	//
	function findByTitle($title)	{
		//
		$sql = "SELECT * FROM reviews WHERE title = :title";
		$statement = $this->db->prepare($sql);
		$statement->execute(array(':title' => $title));

		//
		return $statement->fetch();
	}

	// saves item to database
	public function save($data)	{
		//
		$sql = "INSERT INTO reviews (title, description, full_review, slug, rating, date) VALUES (:title, :description, :full_review, :slug, :rating, :date)";

		//
		$statement = $this->db->prepare($sql);

		//
		$statement->execute(array(
			':title' => $data['title'],
			':description' => $data['description'],
			':full_review' => $data['full_review'],
			':slug' => $data['slug'],
			':rating' => $data['rating'],
			':date' => date('Y-m-d H:i:s')
		));
	}

	// saves item to database
	public function change($data)	{
		//
		$sql = "UPDATE reviews SET
				title = :title,
				description = :description,
				full_review = :full_review,
				slug = :slug,
				rating = :rating WHERE
				id = :id
				LIMIT 1";

		//
		$statement = $this->db->prepare($sql);

		//
		$statement->execute(array(
			':title' => $data['title'],
			':description' => $data['description'],
			':full_review' => $data['full_review'],
			':slug' => $data['slug'],
			':rating' => $data['rating'],
			':id' => $data['id']
		));
	}

	// deletes item to database
	public function delete($id)	{
		//
		$sql = "DELETE FROM reviews WHERE id = :id LIMIT 1";
		$statement = $this->db->prepare($sql);
		$statement->execute(array(':id' => $id));
	}
}