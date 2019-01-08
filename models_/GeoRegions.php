<?php

namespace wdmg\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "geo_regions".
 *
 * @property int $id
 * @property int $country_id
 * @property string $title
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_published
 *
 * @property GeoCities[] $geoCities
 * @property GeoCountries $country
 */
class GeoRegions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'geo_regions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'is_published'], 'integer'],
            [['slug'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 64],
            [['slug'], 'unique'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCountries::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/modules/geo', 'ID'),
            'country_id' => Yii::t('app/modules/geo', 'Country ID'),
            'title' => Yii::t('app/modules/geo', 'Title'),
            'slug' => Yii::t('app/modules/geo', 'Slug'),
            'created_at' => Yii::t('app/modules/geo', 'Created At'),
            'updated_at' => Yii::t('app/modules/geo', 'Updated At'),
            'is_published' => Yii::t('app/modules/geo', 'Is Published'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCities()
    {
        return $this->hasMany(GeoCities::className(), ['region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(GeoCountries::className(), ['id' => 'country_id']);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = GeoRegions::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'country_id' => $this->country_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_published' => $this->is_published,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
