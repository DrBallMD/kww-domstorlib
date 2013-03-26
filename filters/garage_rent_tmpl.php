<div class="domstor_filter">
	<?php $this->displayOpenTag()?>
	<?php $this->displayHidden()?>
	
	<div class="domstor_filter_layout">
		<div class="domstor_filter_fields">
			<table>
				<tr>
					<td class="nasn"><strong>�������� ������:</strong></td>
					<td><?php $this->getField('rent')->displayLabelField('min')?>
		<?php $this->getField('rent')->displayLabelField('max')?> �. <?php $this->getField('rent')->displayLabelField('period')?></td>
				</tr>
				<tr>
					<td class="nasn"><strong>������:</strong></td>
					<td>
						<?php $this->displayLabelField('x_min')?> <?php $this->displayLabelField('x_max')?> �.
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong>�����:</strong></td>
					<td>
						<?php $this->displayLabelField('y_min')?> <?php $this->displayLabelField('y_max')?> �.
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong>������:</strong></td>
					<td>
						<?php $this->displayLabelField('z_min')?> <?php $this->displayLabelField('z_max')?> �.
					</td>
				</tr>
				<tr>
					<td class="nasn"><strong><?php $this->displayLabel('code')?>:</strong></td>
					<td>
						<?php $this->displayField('code')?>
					</td>
				</tr>
			</table>
		</div>
		<div class="domstor_filter_list">
			<table>
				<tr>
					<td class="type">
						<strong><?php $this->displayLabel('type')?>:</strong>
						<?php $this->displayField('type')?>
					</td>
					<td class="district">
						<strong><?php $this->displayLabel('district')?>:</strong>
						<?php $this->displayField('district')?>
					</td>
				</tr>
			</table>		
		</div>
	</div>

	<noscript>
		<div class="center"><?php $this->displayField('submit')?></div>
	</noscript>
	<?php $this->displayCloseTag()?>
	<div class="center"><?php $this->displayField('submit_link')?></div>
</div>
	