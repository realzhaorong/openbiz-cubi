<?php 
class StatisticsListForm extends EasyForm
{
	public function fetchDataSet()
	{
		$resultSet = parent::fetchDataSet();
		$total = 0;
		$newResultSet = array();
		foreach ( $resultSet as $record )
		{
			$total += $record['type_count'];
		}
		foreach ( $resultSet as $record )
		{
			if($total)
			{
				$record['type_percent'] = (int)($record['type_count']/$total*100);
			}
			else
			{
				$record['type_percent'] = 0;
			} 
			$newResultSet[] = $record;
		}
		$this->m_Total = $total;
		return $newResultSet;
	}
}
?>